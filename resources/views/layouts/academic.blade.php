<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Academic Calendar')</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f7fb;
            color: #1f2937;
        }


        /* =========================
           SIDEBAR
        ========================= */

        .sidebar {
            width: 250px;

            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;

            background: #ffffff;

            border-right: 1px solid #e5e7eb;

            padding: 75px 15px 25px;

            z-index: 1000;

            transition: transform 0.3s ease;
        }


        /* Sidebar closed */

        .sidebar.closed {
            transform: translateX(-250px);
        }


        /* =========================
           SIDEBAR TITLE
        ========================= */

        .sidebar-title {
            font-size: 20px;
            font-weight: bold;

            padding: 0 12px;

            margin-bottom: 35px;

            color: #111827;
        }


        /* =========================
           MENU TITLE
        ========================= */

        .menu-title {
            font-size: 12px;

            color: #9ca3af;

            text-transform: uppercase;

            font-weight: bold;

            padding: 0 12px;

            margin-bottom: 10px;
        }


        /* =========================
           MENU
        ========================= */

        .menu {
            display: flex;
            flex-direction: column;

            gap: 5px;
        }


        .menu a {
            text-decoration: none;

            color: #4b5563;

            padding: 12px;

            border-radius: 7px;

            font-size: 14px;

            transition: 0.2s;
        }


        .menu a:hover {
            background: #f3f4f6;

            color: #2563eb;
        }


        .menu a.active {
            background: #eff6ff;

            color: #2563eb;

            font-weight: 600;
        }


        /* =========================
           MAIN CONTENT
        ========================= */

        .main {
            margin-left: 250px;

            width: calc(100% - 250px);

            min-height: 100vh;

            padding: 75px 30px 30px;

            transition:
                margin-left 0.3s ease,
                width 0.3s ease;
        }


        /* Main when sidebar closed */

        .main.full {
            margin-left: 0;

            width: 100%;
        }


        /* =========================
           HAMBURGER
        ========================= */

        .hamburger {
            position: fixed;

            top: 18px;
            left: 18px;

            width: 40px;
            height: 40px;

            border: 1px solid #e5e7eb;

            background: #ffffff;

            border-radius: 8px;

            cursor: pointer;

            display: flex;

            align-items: center;
            justify-content: center;

            font-size: 20px;

            color: #374151;

            z-index: 2000;

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);

            transition: 0.2s;
        }


        .hamburger:hover {
            background: #f3f4f6;

            color: #2563eb;
        }


        /* =========================
           MOBILE
        ========================= */

        @media (max-width: 800px) {

            .sidebar {
                width: 250px;
            }


            .main {
                margin-left: 0;

                width: 100%;

                padding: 75px 20px 20px;
            }


            .sidebar.closed {
                transform: translateX(-250px);
            }

        }

    </style>

    @stack('styles')

</head>


<body>


    <!-- =========================
         HAMBURGER
    ========================= -->

    <button
        type="button"
        class="hamburger"
        id="hamburger"
        onclick="toggleSidebar()"
        aria-label="Toggle sidebar"
    >
        ☰
    </button>


    <!-- =========================
         SIDEBAR
    ========================= -->

    <aside
        class="sidebar"
        id="sidebar"
    >

        <div class="sidebar-title">
            Academic Calendar
        </div>


        <div class="menu-title">
            Calendar
        </div>


        <nav class="menu">


            <!-- Annual Calendar -->

            <a
                href="{{ route('annual_calendar.index') }}"
                class="{{ request()->routeIs('annual_calendar.*') ? 'active' : '' }}"
            >
                Annual Calendar
            </a>


            <!-- Holiday -->

            <a
                href="{{ route('holiday.index') }}"
                class="{{ request()->routeIs('holiday.*') ? 'active' : '' }}"
            >
                Holiday
            </a>


            <!-- Events -->

            <a
    href="{{ route('event.index') }}"
    class="{{ request()->routeIs('event.*') ? 'active' : '' }}"
>
    Events
</a>


            <!-- PTM -->

           <a
    href="{{ route('ptm.index') }}"
    class="{{ request()->routeIs('ptm.*') ? 'active' : '' }}"
>
    PTM
</a>


        </nav>

    </aside>


    <!-- =========================
         MAIN CONTENT
    ========================= -->

    <main
        class="main"
        id="main"
    >

        @yield('content')

    </main>


    <!-- =========================
         JAVASCRIPT
    ========================= -->

    <script>

        function toggleSidebar() {

            const sidebar =
                document.getElementById('sidebar');

            const main =
                document.getElementById('main');


            sidebar.classList.toggle('closed');

            main.classList.toggle('full');

        }

    </script>


    @stack('scripts')


</body>

</html>