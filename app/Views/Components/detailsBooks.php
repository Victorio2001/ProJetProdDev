<?php

$stock_data = $stock_data ?? [];
$cartBook = $cartBook ?? [];
$nextDatetoBeAvaible = $nextDatetoBeAvaible ?? [];
$userHasBook = $userHasBook ?? null;
$countnbInStock = $countnbInStock ?? 0;
$Bookavaible = $userHasBook || $cartBook->nbExemplaires == 0; ?>


<div class="flex justify-center items-center h-full p-8">
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 mt-5 ">
        <div class="flex justify-center items-center bg-gray-100 rounded-lg shadow-md p-4">
            <?php if (isset($cartBook->img))  : ?>
                <img src="<?php echo $cartBook->img; ?>" alt="" class="w-80 h-auto object-cover rounded-lg shadow-md">
            <?php endif; ?>
        </div>
        <div class="flex justify-center bg-gray-100 rounded-lg shadow-md p-4 md:mt-0">
            <div class="max-w-md">
                <p class="mb-4 leading-relaxed">Description : <?php echo $cartBook->description; ?></p>
                <p class="mb-4">Auteur(s)
                    : <?php echo join(', ', array_map(fn($auteur) => $auteur, $cartBook->auteurs)); ?></p>
                <p class="mb-4">Editeur : <?php echo $cartBook->publisher; ?></p>
                <p class="mb-4">Année de parution : <?php echo $cartBook->anneePublication; ?></p>
                <p class="mb-4">ISBN : <?php echo $cartBook->isbn; ?></p>
            </div>
        </div>
        <div class="flex justify-center bg-gray-100 rounded-lg shadow-md p-4 md:mt-0">
            <?php if (empty($nextDatetoBeAvaible['valeur']) && $countnbInStock == 0) : ?>
                <p class="mb-4 text-red-500">Nous n'avons plus ce livre </p>
            <?php else : ?>
                <form method="post" action="/livres/loan" id="myForm">
                    <div class="max-w-md">
                        <input type="hidden" name="book_id" value="<?php echo $cartBook->id; ?>">
                        <?php if ($countnbInStock == 0) : ?>
                            <p class="mb-4 text-red-500">Disponibilité : Rupture de Stock</p>
                        <?php else : ?>
                            <p class="mb-4 text-green-500">Disponibilité : En Stock</p>
                        <?php endif; ?>
                        <?php if ($countnbInStock == 0) : ?>
                            <p class="mb-4">Date de retour estimée : <?= $nextDatetoBeAvaible['valeur']; ?></p>
                        <?php endif; ?>
                        <?php if ($countnbInStock > 0) : ?>
                            <p class="mb-4">Nombre d'exemplaires restants
                                : <?= $countnbInStock; ?></p>
                        <?php endif; ?>
                        <?php if ($countnbInStock > 0): ?>
                            <?php if ($_SESSION['user']->role->id < 3): ?>

                                <label class="mb-4" for="quantity">Nombre d'exemplaires souhaités :</label>
                                <input type="number" min="1" max="<?= $cartBook->nbExemplaires; ?>"
                                       id="quantity" name="quantity" value="1"
                                       class="w-full p-2 rounded-md mb-4 border border-blue-300">
                                <label class="mb-4" for="reservation_date">Date de Réservation :</label>
                                <input id="reservation_date" type="date" min="<?= date('Y-m-d') ?>"
                                       value="<?= date('Y-m-d') ?>" name="reservation_date"
                                       placeholder="Date de réservation souhaitée"
                                       class="w-full p-2 rounded-md mb-4 border border-blue-300">
                            <?php endif; ?>
                            <button type="submit" <?php if ($Bookavaible || $_SESSION['user']->readOnly == 'true') echo "disabled"; ?>
                                    class="px-4 py-2 text-white rounded-2xl w-full <?= $Bookavaible || $_SESSION['user']->readOnly == 'true' ? "bg-gray-400" : "bg-primary-900 hover:bg-primary-700"; ?>">
                                Réserver
                            </button>
                        <?php endif; ?>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
