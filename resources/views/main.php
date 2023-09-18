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
        <a href="/schedules/create" class="flex items-center">
            <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Schedules</span>
        </a>
        <a href="/schedules/create"
           class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
            Create
        </a>
    </div>
</nav>

<div class="mt-8 mx-auto w-3/4 md:max-w-3xl grid grid-cols-1 xl:grid-cols-2 gap-4">
    <? foreach ($schedules as $schedule): ?>
        <a href="#"
           class="block mb-4 mx-auto max-w-md p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
            <h1><?= $schedule['will_at'] ?></h1>

            <ul class="w-80 divide-y divide-gray-200 dark:divide-gray-700">
                <li class="pb-3 sm:pb-4 mt-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img class="w-8 h-8 rounded-full" src="/images/image-default.png" alt="Neil image">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                <?= $schedule['student']['first_name'] ?>
                            </p>
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                <?= $schedule['student']['email'] ?>
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            <?= $schedule['student']['phone'] ?>
                        </div>
                    </div>
                </li>
                <li class="py-3 sm:py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img class="w-8 h-8 rounded-full" src="/images/image-default.png" alt="Neil image">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-white">
                                <?= $schedule['teacher']['first_name'] ?>
                            </p>
                            <p class="text-sm text-gray-500 truncate dark:text-gray-400">
                                <?= $schedule['student']['email'] ?>
                            </p>
                        </div>
                        <div class="inline-flex items-center text-base font-semibold text-gray-900 dark:text-white">
                            <?= $schedule['teacher']['phone'] ?>
                        </div>
                    </div>
                </li>
            </ul>
        </a>


    <? endforeach; ?>
</div>
<div class="flex space-x-2 mt-2 mx-auto w-3/4 md:max-w-3xl flex w-full mb-12 justify-end">
    <? if (empty($links['prev'])): ?>
        <button disabled
                class="flex items-center justify-center px-4 h-10 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg opacity-50">
            Previous
        </button>
    <? else: ?>
        <a href="<?= $urls['prev'] ?>"
           class="flex items-center justify-center px-4 h-10 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            Previous
        </a>
    <? endif; ?>

    <? if (empty($links['next'])): ?>
        <button disabled
                class="flex items-center justify-center px-4 h-10 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg opacity-50">
            Next
        </button>
    <? else: ?>
        <a href="<?= $urls['next'] ?>"
           class="flex items-center justify-center px-4 h-10 text-base font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
            Next
        </a>
    <? endif; ?>
</div>
</body>
</html>