<?php use BibliOlen\Tools\Http; ?>
<?php $params = Http::getUriParams(); ?>
<?php $pageAction = $pageAction ?? []; ?>

<?php $stringParams = array_map(function ($key, $value) {
    return $key . '=' . $value;
}, array_keys($params), $params);

$stringParams = array_filter($stringParams, function ($param) {
    return !str_contains($param, 'page');
}); ?>

<div class="flex justify-center items-center mx-auto mb-4 mt-5">
    <form action="<?php echo $pageAction['path'] ?>" method="get"
          class="w-2/3 px-2 mb-4 flex justify-center items-center align-middle">
        <?php if (!isset($params['filter'])): ?>
            <input type="text" id="filter" name="filter"
                   placeholder="<?php echo $pageAction['messageFilter'] ?>"
                   class="w-full p-2 rounded-md border border-blue-300">
        <?php else: ?>
            <input type="text" id="filter" name="filter" value="<?php echo $params['filter']; ?>"
                   class="w-full p-2 rounded-md border border-blue-300">
        <?php endif; ?>

        <?php foreach ($params as $key => $value): ?>
            <?php if ($key !== 'page' && $key !== 'filter'): ?>
                <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $value; ?>">
            <?php endif; ?>
        <?php endforeach; ?>

        <div class="px-2">
            <button type="submit"
                    class="flex justify-center items-center px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" id="search" x="0px"
                     y="0px" viewBox="0 0 24 24" height="16px" width="16px" xmlns="http://www.w3.org/2000/svg">
                    <g>
                        <path d="M20.031,20.79c0.46,0.46,1.17-0.25,0.71-0.7l-3.75-3.76c1.27-1.41,2.04-3.27,2.04-5.31
		 c0-4.39-3.57-7.96-7.96-7.96s-7.96,3.57-7.96,7.96c0,4.39,3.57,7.96,7.96,7.96c1.98,0,3.81-0.73,5.21-1.94L20.031,20.79z
		 M4.11,11.02c0-3.84,3.13-6.96,6.96-6.96c3.84,0,6.96,3.12,6.96,6.96c0,3.84-3.12,6.96-6.96,6.96C7.24,17.98,4.11,14.86,4.11,11.02
		z"></path>
                    </g>
                </svg>
                <span class="ml-1">Filtrer </span>
            </button>
        </div>
    </form>
    <?php if (isset($add)): ?>
        <div class="mb-4 flex justify-center items-center align-middle">
            <a href="<?php echo $add['path']; ?>"
               class="flex justify-center items-center px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">
                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="16px"
                     width="16px" xmlns="http://www.w3.org/2000/svg">
                    <path d="M416 277.333H277.333V416h-42.666V277.333H96v-42.666h138.667V96h42.666v138.667H416v42.666z"></path>
                </svg>
                <span class="ml-1"><?php echo $add['messageAdd']; ?></span>
            </a>
        </div>
    <?php endif; ?>
</div>
