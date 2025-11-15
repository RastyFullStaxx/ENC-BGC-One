<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shared Services Portal - My Bookings</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

  <!-- Header -->
  <header class="bg-white border-b border-gray-200 fixed top-0 left-0 right-0 z-50">
    <div class="max-w-[1294px] mx-auto flex justify-between items-center px-6 py-4 md:flex-col md:gap-4">
      <div class="flex items-center gap-3 w-full md:justify-between">
        <div class="flex items-center gap-3">
          <img src="https://images.unsplash.com/photo-1614680376593-902f74cf0d41?w=48&h=48&fit=crop" alt="ONE Logo" class="w-12 h-12 rounded-sm object-cover">
          <div>
            <h1 class="text-blue-900 text-lg font-normal">Shared Services Portal</h1>
            <p class="text-gray-500 text-xs">One-Stop Booking Platform</p>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <button class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-md text-sm hover:bg-gray-50">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M8 4.66667V14" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M2 12C1.82319 12 1.65362 11.9298 1.5286 11.8047C1.40357 11.6797 1.33333 11.5101 1.33333 11.3333V2.66667C1.33333 2.48986 1.40357 2.32029 1.5286 2.19526C1.65362 2.07024 1.82319 2 2 2H5.33333C6.04058 2 6.71885 2.28095 7.21895 2.78105C7.71905 3.28115 8 3.95942 8 4.66667C8 3.95942 8.28095 3.28115 8.78105 2.78105C9.28115 2.28095 9.95942 2 10.6667 2H14C14.1768 2 14.3464 2.07024 14.4714 2.19526C14.5964 2.32029 14.6667 2.48986 14.6667 2.66667V11.3333C14.6667 11.5101 14.5964 11.6797 14.4714 11.8047C14.3464 11.9298 14.1768 12 14 12H10C9.46957 12 8.96086 12.2107 8.58579 12.5858C8.21071 12.9609 8 13.4696 8 14C8 13.4696 7.78929 12.9609 7.41421 12.5858C7.03914 12.2107 6.53043 12 6 12H2Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            My Bookings
          </button>
          <span class="bg-blue-600 text-white text-xs px-2 py-0.5 rounded-md">User</span>
          <button class="relative w-9 h-9 flex items-center justify-center">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
              <path d="M8.55667 17.5C8.70296 17.7533 8.91335 17.9637 9.16671 18.11C9.42006 18.2563 9.70746 18.3333 10 18.3333C10.2926 18.3333 10.5799 18.2563 10.8333 18.11C11.0867 17.9637 11.2971 17.7533 11.4433 17.5" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
              <path d="M2.71833 12.7717C2.60947 12.891 2.53763 13.0394 2.51155 13.1988C2.48547 13.3582 2.50627 13.5217 2.57142 13.6695C2.63658 13.8173 2.74328 13.943 2.87855 14.0312C3.01381 14.1195 3.17182 14.1665 3.33333 14.1667H16.6667C16.8282 14.1667 16.9862 14.1199 17.1216 14.0318C17.2569 13.9437 17.3637 13.8181 17.4291 13.6704C17.4944 13.5227 17.5154 13.3592 17.4895 13.1998C17.4637 13.0404 17.392 12.892 17.2833 12.7725C16.175 11.63 15 10.4158 15 6.66667C15 5.34058 14.4732 4.06881 13.5355 3.13113C12.5979 2.19345 11.3261 1.66667 10 1.66667C8.67392 1.66667 7.40215 2.19345 6.46447 3.13113C5.52679 4.06881 5 5.34058 5 6.66667C5 10.4158 3.82417 11.63 2.71833 12.7717Z" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">2</span>
          </button>
          <button class="flex items-center gap-2 px-3 py-1 rounded-md hover:bg-gray-50">
            <div class="w-9 h-9 bg-blue-900 rounded-full flex items-center justify-center text-white">
              <svg width="20" height="20" fill="none" viewBox="0 0 20 20">
                <path d="M15.8333 17.5V15.8333C15.8333 14.9493 15.4821 14.1014 14.857 13.4763C14.2319 12.8512 13.3841 12.5 12.5 12.5H7.5C6.61594 12.5 5.7681 12.8512 5.14298 13.4763C4.51786 14.1014 4.16667 14.9493 4.16667 15.8333V17.5" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M10 9.16667C11.8409 9.16667 13.3333 7.67428 13.3333 5.83333C13.3333 3.99238 11.8409 2.5 10 2.5C8.15905 2.5 6.66667 3.99238 6.66667 5.83333C6.66667 7.67428 8.15905 9.16667 10 9.16667Z" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="text-left">
              <div class="text-blue-900 text-sm">Charles Ramos</div>
              <div class="text-gray-500 text-xs">user.charles@enc.gov</div>
            </div>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M4 6L8 10L12 6" stroke="#6A7282" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </button>
        </div>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main class="max-w-[1294px] mx-auto pt-[108px] px-6 md:pt-[160px] pb-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-6 md:flex-col md:items-start md:gap-4">
      <div>
        <h2 class="text-2xl font-normal text-gray-900 mb-1">My Bookings</h2>
        <p class="text-sm text-gray-500">View and manage all your bookings</p>
      </div>
      <!-- Export Calendar button on the right -->
      <button class="flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-md text-sm hover:bg-gray-50">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
          <path d="M8 10V2" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M14 10V12.6667C14 13.0203 13.8595 13.3594 13.6095 13.6095C13.3594 13.8595 13.0203 14 12.6667 14H3.33333C2.97971 14 2.64057 13.8595 2.39052 13.6095C2.14048 13.3594 2 13.0203 2 12.6667V10" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M4.66667 6.66667L8 10L11.3333 6.66667" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Export Calendar
      </button>
    </div>

    <!-- Tabs -->
    <div class="flex gap-1 bg-gray-200 rounded-xl p-1 mb-6">
      <button class="flex-1 flex items-center justify-center gap-2 bg-white rounded-xl px-4 py-2 text-sm">
        Upcoming <span class="bg-gray-200 text-blue-900 text-xs px-2 py-0.5 rounded-md">2</span>
      </button>
      <button class="flex-1 px-4 py-2 text-sm rounded-xl hover:bg-gray-100">Past</button>
      <button class="flex-1 px-4 py-2 text-sm rounded-xl hover:bg-gray-100">Cancelled</button>
    </div>

    <!-- Filters Card -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 flex flex-col md:flex-row md:items-center gap-3">
      <div class="relative flex-1">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 16 16">
          <path d="M14 14L11.1067 11.1067" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
          <path d="M7.33333 12.6667C10.2789 12.6667 12.6667 10.2789 12.6667 7.33333C12.6667 4.38781 10.2789 2 7.33333 2C4.38781 2 2 4.38781 2 7.33333C2 10.2789 4.38781 12.6667 7.33333 12.6667Z" stroke="currentColor" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        <input type="text" placeholder="Search by purpose, room, or facility..." class="w-full pl-9 pr-3 py-2 rounded-md bg-gray-100 text-gray-600 text-sm border border-transparent focus:border-blue-500 focus:outline-none">
      </div>
      <select class="px-3 py-2 rounded-md bg-gray-100 text-gray-600 text-sm border border-transparent cursor-pointer w-full md:w-auto">
        <option>All Facilities</option>
        <option>Meeting Room</option>
        <option>Conference Room</option>
      </select>
      <select class="px-3 py-2 rounded-md bg-gray-100 text-gray-600 text-sm border border-transparent cursor-pointer w-full md:w-auto">
        <option>All Status</option>
        <option>Pending</option>
        <option>Confirmed</option>
        <option>Cancelled</option>
      </select>
    </div>

    <!-- Bookings Table -->
    <div class="bg-white border border-gray-200 rounded-xl overflow-x-auto">
      <table class="w-full table-auto border-collapse">
        <thead class="border-b border-gray-200">
          <tr>
            <th class="px-2 py-2 text-left text-sm font-normal text-black">Facility</th>
            <th class="px-2 py-2 text-left text-sm font-normal text-black">Date & Time</th>
            <th class="px-2 py-2 text-left text-sm font-normal text-black">Purpose</th>
            <th class="px-2 py-2 text-left text-sm font-normal text-black">Status</th>
            <th class="px-2 py-2 text-right text-sm font-normal text-black">Actions</th>
          </tr>
        </thead>
        <tbody>
          <!-- Example Booking Row -->
          <tr class="border-b border-gray-200 last:border-none">
            <td class="px-2 py-2">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <!-- SVG icon -->
                </div>
                <div>
                  <div class="text-sm text-gray-900">Meeting Room</div>
                  <div class="text-xs text-gray-500">Meeting Room</div>
                </div>
              </div>
            </td>
            <td class="px-2 py-2">
              <div class="flex flex-col gap-0.5 text-sm">
                <div>Sat, Nov 29, 2025</div>
                <div class="text-xs text-gray-500">09:00 - 10:00</div>
              </div>
            </td>
            <td class="px-2 py-2 text-gray-600 text-sm">Victory Group</td>
            <td class="px-2 py-2">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs rounded-md bg-yellow-100 text-yellow-800 border border-yellow-200 capitalize">pending</span>
            </td>
            <td class="px-2 py-2 flex justify-end gap-1">
              <button class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-gray-50">
                <!-- View Icon -->
              </button>
              <button class="w-8 h-8 flex items-center justify-center rounded-md hover:bg-gray-50 text-red-600">
                <!-- Cancel Icon -->
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

  </main>
</body>
</html>
