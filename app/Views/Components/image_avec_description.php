<?php $header_data = $header_data ?? []; ?>

<div class="relative w-full h-[300px]">
    <div class="absolute inset-0 bg-dark-500 bg-opacity-50 flex flex-col justify-center items-center">
        <div class="max-w-6xl px-4">
            <h1 class="text-3xl font-bold text-primary-0 text-center"><?php echo $header_data['titre']; ?></h1>
            <p class="text-primary-0 text-base mt-6 whitespace-pre-wrap text-justify"><?php echo $header_data['description']; ?></p>
            <?php if (isset($header_data['bouton_link'])) :?>
                <div class="flex justify-center items-center mt-4">
                    <a href="<?php echo $header_data['bouton_link'];?>" class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">
                        <?php echo $header_data['bouton_text'];?>
                    </a>
                </div>
            <?php endif?>

            <?php if (isset($header_data['livre_categories'])) :?>
                <div class="flex flex-wrap justify-center items-center mt-4">
                    <p class="text-white text-center mr-2 mb-2 text-xs">Categories :</p>
                    <?php foreach ($header_data['livre_categories'] as $book): ?>
                        <p class="px-4 py-2 bg-primary-900 text-white rounded-md mr-2 mb-2 text-xs"><?php echo $book ?></p>
                    <?php endforeach;?>
                </div>
            <?php endif?>
        </div>
    </div>
    <img class="w-full h-full object-cover" src="<?php echo $header_data['img']; ?>" alt="" width="1920" height="1080"/>
</div>
