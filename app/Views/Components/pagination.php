<?php use BibliOlen\Tools\Http; ?>
<?php $currentPage = $currentPage ?? 1; ?>
<?php $totalPages = $totalPages ?? 1; ?>
<?php $params = Http::getUriParams(); ?>

<?php $stringParams = array_map(function ($key, $value) {
    return $key . '=' . $value;
}, array_keys($params), $params);

$stringParams = array_filter($stringParams, function ($param) {
    return !str_contains($param, 'page');
});?>

<div class="flex justify-center my-12">
    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?php echo $currentPage - 1; ?><?php echo isset($params['filter']) ? '&filter=' . $params['filter'] : ''; ?>"
               class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Previous</span>

                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M11.707 14.293a1 1 0 01-1.414 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L7.414 9H17a1 1 0 010 2h-9.586l4.293 4.293a1 1 0 010 1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </a>
        <?php else: ?>
            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                <span class="sr-only" aria-hidden="true">Previous</span>
                 <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                      aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M11.707 14.293a1 1 0 01-1.414 1.414l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L7.414 9H17a1 1 0 010 2h-9.586l4.293 4.293a1 1 0 010 1.414z"
                          clip-rule="evenodd"/>
                </svg>
            </span>
        <?php endif; ?>


        <div class="flex">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&<?php echo implode('&', $stringParams); ?>"
                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium <?php echo $currentPage === $i ? 'text-white bg-primary-900 hover:bg-primary-700' : 'text-gray-700 bg-white  hover:bg-gray-50'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>

        <?php if ($currentPage < $totalPages): ?>
            <a href="?page=<?php echo $currentPage + 1; ?>&<?php echo implode('&', $stringParams); ?>"
               class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                <span class="sr-only">Suivant</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M8.293 5.293a1 1 0 011.414-1.414l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L12.586 11H3a1 1 0 010-2h9.586L8.293 5.293z"
                          clip-rule="evenodd"/>
                </svg>
            </a>
        <?php else: ?>
            <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500">
                <span class="sr-only"
                      aria-hidden="true">Suivant</span>
                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M8.293 5.293a1 1 0 011.414-1.414l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L12.586 11H3a1 1 0 010-2h9.586L8.293 5.293z"
                          clip-rule="evenodd"/>
                </svg>
            </span>
        <?php endif; ?>

    </nav>
</div>
