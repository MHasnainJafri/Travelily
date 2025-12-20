import type { ApiParams, ApiResponse, TableData, DataFetcher } from "./types"

export async function fetchTableData<T extends TableData>(
  endpoint: string,
  params: ApiParams,
): Promise<ApiResponse<T>> {
  const searchParams = new URLSearchParams()

  searchParams.append("page", params.page.toString())
  searchParams.append("pageSize", params.pageSize.toString())

  if (params.search) {
    searchParams.append("search", params.search)
  }

  if (params.sort && params.sort.length > 0) {
    searchParams.append("sort", JSON.stringify(params.sort))
  }

  if (params.filters && params.filters.length > 0) {
    searchParams.append("filters", JSON.stringify(params.filters))
  }

  const url = `${endpoint}?${searchParams.toString()}`

  try {
    const response = await fetch(url)

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`)
    }

    const data = await response.json()
    // alert("Data fetched successfully from API")
    // return data
    return {
      data: data?.data?.items || data.data  || data.items || data.results || [],
      total: data?.data?.total|| data.total || data.totalCount || data.count || 0,
      page: data?.data?.page  || data.page || data.currentPage || params.page,
      pageSize: data.per_page ||  data.pageSize || data.limit || data.size || params.pageSize,
    }
  } catch (error) {
    console.error("API fetch error:", error)
    throw error
  }
}

// Helper function to process static data (for APIs without pagination)
export function processStaticData<T extends TableData>(data: T[], params: ApiParams): ApiResponse<T> {
  let filteredData = [...data]

  // Apply global search
  if (params.search) {
    filteredData = filteredData.filter((row) =>
      Object.values(row).some((value) => value?.toString().toLowerCase().includes(params.search!.toLowerCase())),
    )
  }

  // Apply column filters
  if (params.filters && params.filters.length > 0) {
    params.filters.forEach((filter) => {
      filteredData = filteredData.filter((row) => {
        const value = row[filter.key]

        switch (filter.type) {
          case "text":
            return filter.operator === "equals"
              ? value === filter.value
              : value?.toString().toLowerCase().includes(filter.value.toLowerCase())

          case "number":
            const numValue = Number.parseFloat(value as string)
            const filterValue = Number.parseFloat(filter.value)

            switch (filter.operator) {
              case "equals":
                return numValue === filterValue
              case "gt":
                return numValue > filterValue
              case "lt":
                return numValue < filterValue
              case "gte":
                return numValue >= filterValue
              case "lte":
                return numValue <= filterValue
              default:
                return numValue === filterValue
            }

          case "select":
            return value === filter.value

          default:
            return true
        }
      })
    })
  }

  // Apply sorting
  if (params.sort && params.sort.length > 0) {
    filteredData.sort((a, b) => {
      for (const sort of params.sort!) {
        const aValue = a[sort.key]
        const bValue = b[sort.key]

        let comparison = 0
        if (aValue < bValue) comparison = -1
        if (aValue > bValue) comparison = 1

        if (comparison !== 0) {
          return sort.direction === "desc" ? -comparison : comparison
        }
      }
      return 0
    })
  }

  // Apply pagination
  const total = filteredData.length
  const startIndex = (params.page - 1) * params.pageSize
  const endIndex = startIndex + params.pageSize
  const paginatedData = filteredData.slice(startIndex, endIndex)

  return {
    data: paginatedData,
    total,
    page: params.page,
    pageSize: params.pageSize,
  }
}

// Create a data fetcher for custom API responses
export function createCustomDataFetcher<T extends TableData>(
  fetchFunction: (params: ApiParams) => Promise<any>,
  dataTransformer?: (response: any) => ApiResponse<T>,
): DataFetcher<T> {
  return async (params: ApiParams) => {
    try {
      const response = await fetchFunction(params)

      if (dataTransformer) {
        return dataTransformer(response)
      }

      // Default transformation - assumes response has data, total, page, pageSize
      return {
        data: response.data || response.items || response.results || [],
        total: response.total || response.totalCount || response.count || 0,
        page: response.page || response.currentPage || params.page,
        pageSize: response.pageSize || response.limit || response.size || params.pageSize,
      }
    } catch (error) {
      console.error("Custom data fetcher error:", error)
      throw error
    }
  }
}
