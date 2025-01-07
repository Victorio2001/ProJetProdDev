<?php $cartBook = $cartBook ?? []; ?>
<?php use BibliOlen\Tools\Tools; ?>

<div class="container mx-auto mt-10 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
    <?php foreach ($cartBook as $book): ?>
        <div class="p-8 bg-white shadow-md flex flex-col items-center  border border-md">
            <div class="flex-shrink-0 mb-4">
                <img src="<?php echo $book->img; ?>" alt="Image" class="w-48 h-56 object-cover">
            </div>
                <h1 class="text-3xl font-bold mb-2 text-primary-900 leading-tight object-cover">
                    <?php echo Tools::truncateStr($book->titre,50); ?>
                </h1>
                <h2 class="text-xl mb-2 justi-start object-cover">
                    <?php echo join(', ', array_map(fn($auteur) => $auteur, $book->auteurs)); ?>
                </h2>
                <p class="text-gray-700 mb-4 object-cover"><?php echo  Tools::truncateStr($book->description,200); ?></p>
                <a href="<?php echo $book->buttonLink; ?>" class="px-4 py-2 bg-primary-900 text-white rounded-2xl hover:bg-primary-700">En savoir plus</a>
        </div>
    <?php endforeach; ?>
</div>
