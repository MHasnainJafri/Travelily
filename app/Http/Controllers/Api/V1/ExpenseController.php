<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    public function store(Request $request, $jamId)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'amount' => 'required|numeric',
            'currency' => 'nullable|string|max:3',
            'category' => 'required|in:food,transport,stay,activity,other',
            'date' => 'required|date',
            'paid_by_user_id' => 'required|exists:users,id',
            'split_with' => 'nullable|array',
            'split_with.*' => 'exists:users,id'
        ]);

        $expense = DB::table('jam_expenses')->insertGetId([
            'jam_id' => $jamId,
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'currency' => $validated['currency'] ?? 'USD',
            'category' => $validated['category'],
            'date' => $validated['date'],
            'paid_by_user_id' => $validated['paid_by_user_id'],
            'split_with' => json_encode($validated['split_with'] ?? []),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $jam = DB::table('jams')->where('id', $jamId)->first();
        $totalSpent = DB::table('jam_expenses')->where('jam_id', $jamId)->sum('amount');
        $remaining = ($jam->budget_max ?? 0) - $totalSpent;

        return response()->json([
            'status' => true,
            'message' => 'Expense added successfully',
            'data' => [
                'id' => $expense,
                'title' => $validated['title'],
                'amount' => $validated['amount'],
                'remaining_jam_budget' => $remaining
            ]
        ]);
    }

    public function getBudget($jamId)
    {
        $jam = DB::table('jams')->where('id', $jamId)->first();
        $expenses = DB::table('jam_expenses')->where('jam_id', $jamId)->get();
        
        $totalSpent = $expenses->sum('amount');
        $remaining = ($jam->budget_max ?? 0) - $totalSpent;
        
        $breakdown = DB::table('jam_expenses')
            ->where('jam_id', $jamId)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->category => $item->total];
            });

        $recentExpenses = DB::table('jam_expenses')
            ->where('jam_id', $jamId)
            ->join('users', 'jam_expenses.paid_by_user_id', '=', 'users.id')
            ->select('jam_expenses.*', 'users.name as paid_by')
            ->orderBy('jam_expenses.created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'total_budget' => $jam->budget_max ?? 0,
                'total_spent' => $totalSpent,
                'remaining' => $remaining,
                'breakdown' => $breakdown,
                'recent_expenses' => $recentExpenses
            ]
        ]);
    }

    public function update(Request $request, $expenseId)
    {
        $validated = $request->validate([
            'title' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'category' => 'nullable|in:food,transport,stay,activity,other',
            'date' => 'nullable|date',
        ]);

        DB::table('jam_expenses')
            ->where('id', $expenseId)
            ->update(array_merge($validated, ['updated_at' => now()]));

        return response()->json([
            'status' => true,
            'message' => 'Expense updated successfully'
        ]);
    }

    public function destroy($expenseId)
    {
        DB::table('jam_expenses')->where('id', $expenseId)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Expense deleted successfully'
        ]);
    }

    public function updateBudget(Request $request, $jamId)
    {
        $validated = $request->validate([
            'budget_min' => 'required|numeric|min:0',
            'budget_max' => 'required|numeric|min:0',
        ]);

        DB::table('jams')->where('id', $jamId)->update([
            'budget_min' => $validated['budget_min'],
            'budget_max' => $validated['budget_max'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Budget updated successfully'
        ]);
    }
}
