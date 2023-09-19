<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="/build.css">
    <title>Document</title>
</head>
<body>

<nav class="border-gray-200 bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="/" class="flex items-center">
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Schedules</span>
        </a>
        <a href="/schedules/create"
           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Create
        </a>
    </div>
</nav>

<form method="post" action="/schedules/create" class="mt-4 mx-auto max-w-2xl w-3/4">
    <? if (isset($_SESSION['message'])): ?>
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
            <span class="font-medium">
                <?= $_SESSION['message'] ?>
                <?php unset($_SESSION['message']) ?>
            </span>
        </div>
    <? endif; ?>

    <label for="countries" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Student</label>
    <select name="student_id" id="countries"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <? foreach ($students as $student): ?>
            <option value="<?= $student->getId() ?>">
                <?= $student->getFirstName() ?>
            </option>
        <? endforeach; ?>
    </select>
    <label for="countries" class="mt-4 block mb-2 text-sm font-medium text-gray-900 dark:text-white">Teacher</label>
    <select name="teacher_id" id="countries"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
        <? foreach ($teachers as $teacher): ?>
            <option value="<?= $teacher->getId() ?>">
                <?= $teacher->getFirstName() ?>
            </option>
        <? endforeach; ?>
        </option>
    </select>
    <div class="relative max-w-sm mb-4">
        <div
                x-data
                x-init="flatpickr($refs.datetimewidget, {wrap: true, enableTime: true, dateFormat: 'M j, Y h:i K'});"
                x-ref="datetimewidget"
                class="flatpickr container mx-auto col-span-6 sm:col-span-6 mt-5"
        >
            <label for="datetime" class="flex-grow  block font-medium text-sm text-gray-700 mb-1">Date and Time</label>
            <div class="flex align-middle align-content-center">
                <input name="will_at"
                       x-ref="datetime"
                       type="text"
                       id="datetime"
                       data-input
                       placeholder="Select.."
                       class="block w-full px-2 focus:outline-none border border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-l-md shadow-sm"

                >

                <a
                        class="h-11 w-10 input-button cursor-pointer rounded-r-md bg-transparent border-gray-300 border-t border-b border-r"
                        title="clear" data-clear
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 mt-2 ml-1" viewBox="0 0 20 20"
                         fill="#c53030">
                        <path fill-rule="evenodd"
                              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                              clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>

        </div>
    </div>
    <button type="submit"
            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        Submit
    </button>
</form>

<script type="module" src="/flatpickr/dist/flatpickr.min.js"></script>
<link rel="stylesheet" href="/flatpickr/dist/themes/airbnb.css">
<script src="/alpinejs/dist/cdn.min.js" defer></script>
</body>
</html>