import { ComponentType } from 'react';
export interface Stat {
  title: string;
  value: string; // Could be number if values are numeric (e.g., 12845)
  change: string; // e.g., "+12%"
  trend: 'up' | 'down'; // Literal type for trend
  icon: ComponentType; // Generic type for React component (e.g., Users icon)
  color: string; // e.g., "bg-blue-500"
}