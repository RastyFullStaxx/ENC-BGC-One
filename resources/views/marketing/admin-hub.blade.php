<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Hub - Shared Services Portal</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-section">
                <img src="https://raw.githubusercontent.com/figma/code-connect/main/examples/nextjs/public/logo.png" alt="ONE" class="logo-image">
                <div class="logo-text">
                    <h1 class="logo-title">Shared Services Portal</h1>
                    <p class="logo-subtitle">One-Stop Booking Platform</p>
                </div>
            </div>
            <div class="header-nav">
                <button class="nav-button nav-button-primary">
                    <svg class="nav-icon" fill="none" viewBox="0 0 16 16">
                        <path d="M13.3333 8.66668C13.3333 12 11 13.6667 8.22667 14.6333C8.08144 14.6826 7.92369 14.6802 7.78 14.6267C5 13.6667 2.66667 12 2.66667 8.66668V4.00001C2.66667 3.8232 2.7369 3.65363 2.86193 3.52861C2.98695 3.40359 3.15652 3.33335 3.33333 3.33335C4.66667 3.33335 6.33333 2.53335 7.49333 1.52001C7.63457 1.39935 7.81424 1.33305 8 1.33305C8.18576 1.33305 8.36543 1.39935 8.50667 1.52001C9.67333 2.54001 11.3333 3.33335 12.6667 3.33335C12.8435 3.33335 13.013 3.40359 13.1381 3.52861C13.2631 3.65363 13.3333 3.8232 13.3333 4.00001V8.66668Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Admin Hub</span>
                </button>
                <button class="nav-button nav-button-secondary">
                    <svg class="nav-icon" fill="none" viewBox="0 0 16 16">
                        <path d="M8 4.66667V14" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M2 12C1.82319 12 1.65362 11.9298 1.5286 11.8047C1.40357 11.6797 1.33333 11.5101 1.33333 11.3333V2.66667C1.33333 2.48986 1.40357 2.32029 1.5286 2.19526C1.65362 2.07024 1.82319 2 2 2H5.33333C6.04058 2 6.71885 2.28095 7.21895 2.78105C7.71905 3.28115 8 3.95942 8 4.66667C8 3.95942 8.28095 3.28115 8.78105 2.78105C9.28115 2.28095 9.95942 2 10.6667 2H14C14.1768 2 14.3464 2.07024 14.4714 2.19526C14.5964 2.32029 14.6667 2.48986 14.6667 2.66667V11.3333C14.6667 11.5101 14.5964 11.6797 14.4714 11.8047C14.3464 11.9298 14.1768 12 14 12H10C9.46957 12 8.96086 12.2107 8.58579 12.5858C8.21071 12.9609 8 13.4696 8 14C8 13.4696 7.78929 12.9609 7.41421 12.5858C7.03914 12.2107 6.53043 12 6 12H2Z" stroke="black" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>My Bookings</span>
                </button>
                <span class="admin-badge">
                    <svg class="badge-icon" fill="none" viewBox="0 0 12 12">
                        <path d="M10 6.5C10 9 8.25 10.25 6.17 10.975C6.06108 11.0119 5.94277 11.0101 5.835 10.97C3.75 10.25 2 9 2 6.5V3C2 2.86739 2.05268 2.74021 2.14645 2.64645C2.24021 2.55268 2.36739 2.5 2.5 2.5C3.5 2.5 4.75 1.9 5.62 1.14C5.72593 1.0495 5.86068 0.999775 6 0.999775C6.13932 0.999775 6.27407 1.0495 6.38 1.14C7.255 1.905 8.5 2.5 9.5 2.5C9.63261 2.5 9.75979 2.55268 9.85355 2.64645C9.94732 2.74021 10 2.86739 10 3V6.5Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span>Admin</span>
                </span>
                <button class="notification-button">
                    <svg class="notification-icon" fill="none" viewBox="0 0 20 20">
                        <path d="M2.71833 12.7717C2.60947 12.891 2.53763 13.0394 2.51155 13.1988C2.48547 13.3582 2.50627 13.5217 2.57142 13.6695C2.63658 13.8173 2.74328 13.943 2.87855 14.0312C3.01381 14.1195 3.17182 14.1665 3.33333 14.1667H16.6667C16.8282 14.1667 16.9862 14.1199 17.1216 14.0318C17.2569 13.9437 17.3637 13.8181 17.4291 13.6704C17.4944 13.5227 17.5154 13.3592 17.4895 13.1998C17.4637 13.0404 17.392 12.892 17.2833 12.7725C16.175 11.63 15 10.4158 15 6.66667C15 5.34058 14.4732 4.06881 13.5355 3.13113C12.5979 2.19345 11.3261 1.66667 10 1.66667C8.67392 1.66667 7.40215 2.19345 6.46447 3.13113C5.52679 4.06881 5 5.34058 5 6.66667C5 10.4158 3.82417 11.63 2.71833 12.7717Z" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M8.55667 17.5C8.70296 17.7533 8.91335 17.9637 9.16671 18.11C9.42006 18.2563 9.70746 18.3333 10 18.3333C10.2926 18.3333 10.5799 18.2563 10.8333 18.11C11.0867 17.9637 11.2971 17.7533 11.4433 17.5" stroke="#4A5565" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    <span class="notification-badge">2</span>
                </button>
                <button class="user-button">
                    <div class="user-avatar">
                        <svg class="user-icon" fill="none" viewBox="0 0 20 20">
                            <path d="M15.8333 17.5V15.8333C15.8333 14.9493 15.4821 14.1014 14.857 13.4763C14.2319 12.8512 13.3841 12.5 12.5 12.5H7.5C6.61594 12.5 5.7681 12.8512 5.14298 13.4763C4.51786 14.1014 4.16667 14.9493 4.16667 15.8333V17.5" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 9.16667C11.8409 9.16667 13.3333 7.67428 13.3333 5.83333C13.3333 3.99238 11.8409 2.5 10 2.5C8.15905 2.5 6.66667 3.99238 6.66667 5.83333C6.66667 7.67428 8.15905 9.16667 10 9.16667Z" stroke="white" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="user-info">
                        <p class="user-name">Charles Ramos</p>
                        <p class="user-email">charles@enc.gov</p>
                    </div>
                    <svg class="dropdown-icon" fill="none" viewBox="0 0 16 16">
                        <path d="M4 6L8 10L12 6" stroke="#6A7282" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-icon">
                <svg fill="none" viewBox="0 0 24 24">
                    <path d="M20 13C20 18 16.5 20.5 12.34 21.95C12.1222 22.0238 11.8855 22.0203 11.67 21.94C7.5 20.5 4 18 4 13V6C4 5.73478 4.10536 5.48043 4.29289 5.29289C4.48043 5.10536 4.73478 5 5 5C7 5 9.5 3.8 11.24 2.28C11.4519 2.099 11.7214 1.99955 12 1.99955C12.2786 1.99955 12.5481 2.099 12.76 2.28C14.51 3.81 17 5 19 5C19.2652 5 19.5196 5.10536 19.7071 5.29289C19.8946 5.48043 20 5.73478 20 6V13Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="page-title-section">
                <h1 class="page-title">Admin Hub</h1>
                <p class="page-subtitle">System administration and management</p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="stats-container">
            <div class="stat-card">
                <h3 class="stat-card-title">Total Users</h3>
                <div class="stat-card-content">
                    <p class="stat-number">0</p>
                    <p class="stat-label">Active accounts</p>
                </div>
            </div>
            <div class="stat-card">
                <h3 class="stat-card-title">Total Bookings</h3>
                <div class="stat-card-content">
                    <p class="stat-number">0</p>
                    <p class="stat-label">All time</p>
                </div>
            </div>
            <div class="stat-card">
                <h3 class="stat-card-title">Facilities</h3>
                <div class="stat-card-content">
                    <p class="stat-number">2</p>
                    <p class="stat-label">Meeting rooms available</p>
                </div>
            </div>
        </div>

        <!-- Administration Modules -->
        <section class="modules-section">
            <h2 class="section-title">Administration Modules</h2>
            <div class="modules-grid">
                <div class="module-card module-disabled">
                    <div class="module-icon module-icon-blue">
                        <svg fill="none" viewBox="0 0 20 20">
                            <path d="M8.33334 10H11.6667" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M8.33334 6.66667H11.6667" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M11.6667 17.5V15C11.6667 14.558 11.4911 14.134 11.1785 13.8215C10.866 13.5089 10.442 13.3333 10 13.3333C9.55798 13.3333 9.13405 13.5089 8.82149 13.8215C8.50893 14.134 8.33334 14.558 8.33334 15V17.5" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 8.33333H3.33333C2.89131 8.33333 2.46738 8.50893 2.15482 8.82149C1.84226 9.13405 1.66667 9.55797 1.66667 10V15.8333C1.66667 16.2754 1.84226 16.6993 2.15482 17.0118C2.46738 17.3244 2.89131 17.5 3.33333 17.5H16.6667C17.1087 17.5 17.5326 17.3244 17.8452 17.0118C18.1577 16.6993 18.3333 16.2754 18.3333 15.8333V7.5C18.3333 7.05797 18.1577 6.63405 17.8452 6.32149C17.5326 6.00893 17.1087 5.83333 16.6667 5.83333H15" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5 17.5V4.16667C5 3.72464 5.1756 3.30072 5.48816 2.98816C5.80072 2.67559 6.22464 2.5 6.66667 2.5H13.3333C13.7754 2.5 14.1993 2.67559 14.5118 2.98816C14.8244 3.30072 15 3.72464 15 4.16667V17.5" stroke="#155DFC" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="module-content">
                        <div class="module-title-row">
                            <h3 class="module-title">Facilities Management</h3>
                            <span class="coming-soon-badge">Coming Soon</span>
                        </div>
                        <p class="module-description">Manage rooms, equipment, and resources</p>
                    </div>
                </div>

                <div class="module-card module-disabled">
                    <div class="module-icon module-icon-green">
                        <svg fill="none" viewBox="0 0 20 20">
                            <path d="M13.3333 17.5V15.8333C13.3333 14.9493 12.9821 14.1014 12.357 13.4763C11.7319 12.8512 10.8841 12.5 10 12.5H5C4.11595 12.5 3.2681 12.8512 2.64298 13.4763C2.01786 14.1014 1.66667 14.9493 1.66667 15.8333V17.5" stroke="#00A63E" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3333 2.60666C14.0481 2.79197 14.6812 3.20938 15.1331 3.79338C15.585 4.37738 15.8302 5.0949 15.8302 5.83333C15.8302 6.57175 15.585 7.28927 15.1331 7.87327C14.6812 8.45727 14.0481 8.87468 13.3333 9.05999" stroke="#00A63E" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M18.3333 17.5V15.8333C18.3328 15.0948 18.087 14.3773 17.6345 13.7936C17.182 13.2099 16.5484 12.793 15.8333 12.6083" stroke="#00A63E" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.5 9.16667C9.34095 9.16667 10.8333 7.67428 10.8333 5.83333C10.8333 3.99238 9.34095 2.5 7.5 2.5C5.65905 2.5 4.16667 3.99238 4.16667 5.83333C4.16667 7.67428 5.65905 9.16667 7.5 9.16667Z" stroke="#00A63E" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="module-content">
                        <div class="module-title-row">
                            <h3 class="module-title">User Management</h3>
                            <span class="coming-soon-badge">Coming Soon</span>
                        </div>
                        <p class="module-description">Manage user accounts and permissions</p>
                    </div>
                </div>

                <div class="module-card module-disabled">
                    <div class="module-icon module-icon-purple">
                        <svg fill="none" viewBox="0 0 20 20">
                            <path d="M2.5 2.5V15.8333C2.5 16.2754 2.67559 16.6993 2.98816 17.0118C3.30072 17.3244 3.72464 17.5 4.16667 17.5H17.5" stroke="#9810FA" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15 14.1667V7.5" stroke="#9810FA" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10.8333 14.1667V4.16667" stroke="#9810FA" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M6.66667 14.1667V11.6667" stroke="#9810FA" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="module-content">
                        <div class="module-title-row">
                            <h3 class="module-title">Analytics & Reports</h3>
                            <span class="coming-soon-badge">Coming Soon</span>
                        </div>
                        <p class="module-description">View usage statistics and generate reports</p>
                    </div>
                </div>

                <div class="module-card module-disabled">
                    <div class="module-icon module-icon-orange">
                        <svg fill="none" viewBox="0 0 20 20">
                            <path d="M8.05917 3.44669C8.10508 2.96364 8.32944 2.51506 8.68842 2.18859C9.04739 1.86212 9.51519 1.68121 10.0004 1.68121C10.4856 1.68121 10.9534 1.86212 11.3124 2.18859C11.6714 2.51506 11.8957 2.96364 11.9417 3.44669C11.9693 3.75874 12.0716 4.05954 12.2401 4.32364C12.4086 4.58774 12.6382 4.80737 12.9096 4.96392C13.1809 5.12048 13.486 5.20936 13.7989 5.22304C14.1119 5.23672 14.4235 5.1748 14.7075 5.04252C15.1484 4.84234 15.6481 4.81337 16.1092 4.96126C16.5703 5.10915 16.9599 5.42332 17.2021 5.84261C17.4443 6.26191 17.5219 6.75633 17.4197 7.22967C17.3175 7.703 17.0428 8.12137 16.6492 8.40336C16.3928 8.58323 16.1836 8.8222 16.0391 9.10005C15.8946 9.37789 15.8192 9.68645 15.8192 9.99961C15.8192 10.3128 15.8946 10.6213 16.0391 10.8992C16.1836 11.177 16.3928 11.416 16.6492 11.5959C17.0428 11.8778 17.3175 12.2962 17.4197 12.7695C17.5219 13.2429 17.4443 13.7373 17.2021 14.1566C16.9599 14.5759 16.5703 14.8901 16.1092 15.038C15.6481 15.1858 15.1484 15.1569 14.7075 14.9567C14.4235 14.8244 14.1119 14.7625 13.7989 14.7762C13.486 14.7899 13.1809 14.8787 12.9096 15.0353C12.6382 15.1918 12.4086 15.4115 12.2401 15.6756C12.0716 15.9397 11.9693 16.2405 11.9417 16.5525C11.8957 17.0356 11.6714 17.4842 11.3124 17.8106C10.9534 18.1371 10.4856 18.318 10.0004 18.318C9.51519 18.318 9.04739 18.1371 8.68842 17.8106C8.32944 17.4842 8.10508 17.0356 8.05917 16.5525C8.03162 16.2404 7.92925 15.9394 7.76072 15.6753C7.59219 15.4111 7.36248 15.1914 7.09103 15.0348C6.81958 14.8782 6.51439 14.7894 6.20132 14.7758C5.88824 14.7622 5.5765 14.8242 5.2925 14.9567C4.85157 15.1569 4.35194 15.1858 3.89083 15.038C3.42973 14.8901 3.04014 14.5759 2.7979 14.1566C2.55566 13.7373 2.47809 13.2429 2.5803 12.7695C2.6825 12.2962 2.95717 11.8778 3.35083 11.5959C3.60718 11.416 3.81644 11.177 3.96091 10.8992C4.10537 10.6213 4.18079 10.3128 4.18079 9.99961C4.18079 9.68645 4.10537 9.37789 3.96091 9.10005C3.81644 8.8222 3.60718 8.58323 3.35083 8.40336C2.95772 8.12123 2.68354 7.70302 2.58158 7.23001C2.47963 6.757 2.55717 6.26297 2.79915 5.84395C3.04113 5.42493 3.43026 5.11084 3.89091 4.96272C4.35156 4.81461 4.85082 4.84305 5.29167 5.04252C5.57563 5.1748 5.88729 5.23672 6.20025 5.22304C6.51321 5.20936 6.81828 5.12048 7.08961 4.96392C7.36095 4.80737 7.59058 4.58774 7.75905 4.32364C7.92753 4.05954 8.0299 3.75874 8.0575 3.44669" stroke="#F54900" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z" stroke="#F54900" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="module-content">
                        <div class="module-title-row">
                            <h3 class="module-title">System Settings</h3>
                            <span class="coming-soon-badge">Coming Soon</span>
                        </div>
                        <p class="module-description">Configure platform settings and policies</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- System Status -->
        <section class="status-card">
            <div class="status-header">
                <svg class="status-icon" fill="none" viewBox="0 0 20 20">
                    <g clip-path="url(#clip0_1_389)">
                        <path d="M18.3333 10H16.2667C15.9025 9.99922 15.548 10.1178 15.2576 10.3375C14.9672 10.5572 14.7567 10.866 14.6583 11.2167L12.7 18.1833C12.6874 18.2266 12.6611 18.2646 12.625 18.2917C12.5889 18.3187 12.5451 18.3333 12.5 18.3333C12.4549 18.3333 12.4111 18.3187 12.375 18.2917C12.3389 18.2646 12.3126 18.2266 12.3 18.1833L7.7 1.81667C7.68738 1.77339 7.66106 1.73538 7.625 1.70833C7.58894 1.68129 7.54508 1.66667 7.5 1.66667C7.45492 1.66667 7.41106 1.68129 7.375 1.70833C7.33894 1.73538 7.31262 1.77339 7.3 1.81667L5.34167 8.78333C5.24372 9.13263 5.03448 9.44043 4.74572 9.66001C4.45696 9.87959 4.10443 9.99896 3.74167 10H1.66667" stroke="#00A63E" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                    <defs>
                        <clipPath id="clip0_1_389">
                            <rect fill="white" height="20" width="20"/>
                        </clipPath>
                    </defs>
                </svg>
                <h3 class="status-title">System Status</h3>
            </div>
            <div class="status-list">
                <div class="status-item">
                    <span class="status-name">Database</span>
                    <span class="status-badge status-operational">Operational</span>
                </div>
                <div class="status-item">
                    <span class="status-name">Authentication</span>
                    <span class="status-badge status-operational">Operational</span>
                </div>
                <div class="status-item status-item-last">
                    <span class="status-name">Booking System</span>
                    <span class="status-badge status-operational">Operational</span>
                </div>
            </div>
        </section>

        <!-- Info Banner -->
        <div class="info-banner">
            <div class="info-icon">
                <svg fill="none" viewBox="0 0 16 16">
                    <path d="M13.3333 8.66668C13.3333 12 11 13.6667 8.22667 14.6333C8.08144 14.6826 7.92369 14.6802 7.78 14.6267C5 13.6667 2.66667 12 2.66667 8.66668V4.00001C2.66667 3.8232 2.7369 3.65363 2.86193 3.52861C2.98695 3.40359 3.15652 3.33335 3.33333 3.33335C4.66667 3.33335 6.33333 2.53335 7.49333 1.52001C7.63457 1.39935 7.81424 1.33305 8 1.33305C8.18576 1.33305 8.36543 1.39935 8.50667 1.52001C9.67333 2.54001 11.3333 3.33335 12.6667 3.33335C12.8435 3.33335 13.013 3.40359 13.1381 3.52861C13.2631 3.65363 13.3333 3.8232 13.3333 4.00001V8.66668Z" stroke="white" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="info-content">
                <h4 class="info-title">Admin Hub Reset</h4>
                <p class="info-text">The system has been reset to a clean state. All approval queues and mock data have been removed. Admin modules are marked as "Coming Soon" and will be developed as needed.</p>
            </div>
        </div>
    </main>

    <script>
// Admin Hub Dashboard JavaScript

// Initialize the dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('Admin Hub Dashboard loaded');
    
    // Add hover effects to module cards
    const moduleCards = document.querySelectorAll('.module-card');
    moduleCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            if (!this.classList.contains('module-disabled')) {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            }
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
        
        card.addEventListener('click', function() {
            if (this.classList.contains('module-disabled')) {
                alert('This module is coming soon!');
            }
        });
    });
    
    // Add click handlers for navigation buttons
    const adminHubBtn = document.querySelector('.nav-button-primary');
    const myBookingsBtn = document.querySelector('.nav-button-secondary');
    
    if (adminHubBtn) {
        adminHubBtn.addEventListener('click', function() {
            console.log('Admin Hub clicked');
            // Already on Admin Hub page
        });
    }
    
    if (myBookingsBtn) {
        myBookingsBtn.addEventListener('click', function() {
            console.log('My Bookings clicked');
            alert('My Bookings page would be loaded here');
        });
    }
    
    // Handle notification button click
    const notificationBtn = document.querySelector('.notification-button');
    if (notificationBtn) {
        notificationBtn.addEventListener('click', function() {
            console.log('Notifications clicked');
            alert('You have 2 new notifications');
        });
    }
    
    // Handle user dropdown button
    const userBtn = document.querySelector('.user-button');
    if (userBtn) {
        userBtn.addEventListener('click', function() {
            console.log('User menu clicked');
            alert('User menu options:\n- Profile\n- Settings\n- Logout');
        });
    }
    
    // Add smooth transitions to cards
    const cards = document.querySelectorAll('.stat-card, .status-card');
    cards.forEach(card => {
        card.style.transition = 'all 0.3s ease';
    });
    
    // Simulate data loading (optional enhancement)
    setTimeout(() => {
        console.log('Dashboard data loaded');
    }, 500);
});

// Function to update stats (can be called with real data)
function updateStats(users, bookings, facilities) {
    const statNumbers = document.querySelectorAll('.stat-number');
    if (statNumbers.length >= 3) {
        statNumbers[0].textContent = users;
        statNumbers[1].textContent = bookings;
        statNumbers[2].textContent = facilities;
    }
}

// Function to update system status
function updateSystemStatus(database, authentication, bookingSystem) {
    const statusBadges = document.querySelectorAll('.status-badge');
    const statuses = [database, authentication, bookingSystem];
    
    statusBadges.forEach((badge, index) => {
        if (statuses[index]) {
            badge.textContent = statuses[index];
            badge.className = 'status-badge';
            
            if (statuses[index].toLowerCase() === 'operational') {
                badge.classList.add('status-operational');
            } else {
                badge.style.backgroundColor = '#fee2e2';
                badge.style.color = '#dc2626';
            }
        }
    });
}

// Export functions for external use
window.AdminHub = {
    updateStats,
    updateSystemStatus
};

</script>
</body>

<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f8fafc;
    color: #101828;
    overflow-x: hidden;
}

/* Header Styles */
.header {
    background: white;
    border-bottom: 0.8px solid #e5e7eb;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 100;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 24px;
    height: 84.8px;
}

.logo-section {
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-image {
    width: 48px;
    height: 48px;
    object-fit: contain;
}

.logo-title {
    font-size: 20px;
    line-height: 28px;
    color: #1c398e;
    font-weight: normal;
}

.logo-subtitle {
    font-size: 12px;
    line-height: 16px;
    color: #6a7282;
}

.header-nav {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-button {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    height: 36px;
    padding: 0 12px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    line-height: 20px;
    font-family: Arial, sans-serif;
    transition: opacity 0.2s;
}

.nav-button:hover {
    opacity: 0.9;
}

.nav-button-primary {
    background-color: #1c398e;
    color: white;
}

.nav-button-secondary {
    background-color: white;
    color: black;
    border: 0.8px solid #e2e8f0;
}

.nav-icon {
    width: 16px;
    height: 16px;
}

.admin-badge {
    display: flex;
    align-items: center;
    gap: 4px;
    background-color: #fb2c36;
    color: white;
    padding: 2.8px 8.8px;
    border-radius: 6px;
    font-size: 12px;
    line-height: 16px;
    height: 21.587px;
}

.badge-icon {
    width: 12px;
    height: 12px;
}

.notification-button {
    position: relative;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.notification-icon {
    width: 20px;
    height: 20px;
}

.notification-badge {
    position: absolute;
    top: -4px;
    right: -4px;
    width: 20px;
    height: 20px;
    background-color: #fb2c36;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    line-height: 16px;
}

.user-button {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 0 12px;
    height: 52px;
    border-radius: 8px;
    border: none;
    background: transparent;
    cursor: pointer;
    transition: background-color 0.2s;
}

.user-button:hover {
    background-color: #f8fafc;
}

.user-avatar {
    width: 36px;
    height: 36px;
    background-color: #1c398e;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.user-icon {
    width: 20px;
    height: 20px;
}

.user-info {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}

.user-name {
    font-size: 14px;
    line-height: 20px;
    color: #1c398e;
}

.user-email {
    font-size: 12px;
    line-height: 16px;
    color: #6a7282;
}

.dropdown-icon {
    width: 16px;
    height: 16px;
}

/* Main Content */
.main-content {
    margin-top: 84.8px;
    padding: 24px;
    max-width: 1294px;
}

/* Page Header */
.page-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}

.page-icon {
    width: 48px;
    height: 48px;
    background-color: #1c398e;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.page-icon svg {
    width: 24px;
    height: 24px;
}

.page-title {
    font-size: 24px;
    line-height: 32px;
    color: #101828;
    font-weight: normal;
}

.page-subtitle {
    font-size: 14px;
    line-height: 20px;
    color: #6a7282;
}

/* Stats Cards */
.stats-container {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    border: 0.8px solid #e5e7eb;
    border-radius: 12px;
    padding: 24.8px;
}

.stat-card-title {
    font-size: 14px;
    line-height: 20px;
    color: #6a7282;
    font-weight: normal;
    margin-bottom: 38px;
}

.stat-card-content {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-number {
    font-size: 30px;
    line-height: 36px;
    color: #101828;
}

.stat-label {
    font-size: 12px;
    line-height: 16px;
    color: #6a7282;
}

/* Modules Section */
.modules-section {
    margin-bottom: 24px;
}

.section-title {
    font-size: 18px;
    line-height: 28px;
    color: #101828;
    font-weight: normal;
    margin-bottom: 16px;
}

.modules-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

.module-card {
    background: white;
    border: 1.6px solid #e5e7eb;
    border-radius: 12px;
    padding: 25.6px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.module-disabled {
    opacity: 0.6;
}

.module-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.module-icon svg {
    width: 20px;
    height: 20px;
}

.module-icon-blue {
    background-color: #dbeafe;
}

.module-icon-green {
    background-color: #dcfce7;
}

.module-icon-purple {
    background-color: #f3e8ff;
}

.module-icon-orange {
    background-color: #ffedd4;
}

.module-content {
    flex: 1;
}

.module-title-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 4px;
    position: relative;
}

.module-title {
    font-size: 16px;
    line-height: 24px;
    color: #000000;
    font-weight: normal;
}

.coming-soon-badge {
    background-color: #e5e7eb;
    color: #4a5565;
    font-size: 12px;
    line-height: 16px;
    padding: 2.8px 8.8px;
    border-radius: 6px;
}

.module-description {
    font-size: 16px;
    line-height: 24px;
    color: #64748b;
}

/* System Status Card */
.status-card {
    background: white;
    border: 0.8px solid #e5e7eb;
    border-radius: 12px;
    padding: 24.8px;
    margin-bottom: 24px;
}

.status-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 30px;
}

.status-icon {
    width: 20px;
    height: 20px;
}

.status-title {
    font-size: 16px;
    line-height: 20px;
    color: #000000;
    font-weight: normal;
}

.status-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.status-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    border-bottom: 0.8px solid #f3f4f6;
}

.status-item-last {
    border-bottom: none;
    padding-bottom: 0;
}

.status-name {
    font-size: 14px;
    line-height: 20px;
    color: #4a5565;
}

.status-badge {
    padding: 2.8px 8.8px;
    border-radius: 6px;
    font-size: 12px;
    line-height: 16px;
}

.status-operational {
    background-color: #dcfce7;
    color: #008236;
}

/* Info Banner */
.info-banner {
    background-color: #eff6ff;
    border: 0.8px solid #bedbff;
    border-radius: 8px;
    padding: 16.8px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.info-icon {
    width: 32px;
    height: 32px;
    background-color: #155dfc;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.info-icon svg {
    width: 16px;
    height: 16px;
}

.info-content {
    flex: 1;
}

.info-title {
    font-size: 14px;
    line-height: 20px;
    color: #1c398e;
    font-weight: bold;
    margin-bottom: 4px;
}

.info-text {
    font-size: 14px;
    line-height: 20px;
    color: #193cb8;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .stats-container {
        grid-template-columns: 1fr;
    }

    .modules-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        height: auto;
        gap: 16px;
    }

    .header-nav {
        width: 100%;
        flex-wrap: wrap;
        justify-content: center;
    }

    .main-content {
        padding: 16px;
    }

    .user-button {
        padding: 0 8px;
    }

    .user-info {
        display: none;
    }
}
</style>

</html>
