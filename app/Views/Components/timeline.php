<?php $timelineItems = $timelineItems ?? []; ?>

<?php if (empty($timelineItems)): ?>
    <main class="flex justify-center items-center mt-8 ">
        <div class="w-full md:w-2/3 lg:w-1/2 xl:w-1/3 bg-white shadow-md rounded-lg p-8 text-center">
            <h2 class="text-3xl text-gray-800 font-bold mb-6">
                Votre historique est vide, car vous n’avez pas encore emprunté de livre.
            </h2>
        </div>
    </main>
<?php endif; ?>

<main class="relative flex flex-col justify-center overflow-hidden">
    <div class="w-full max-w-6xl mx-auto px-4 md:px-6 ">
        <div class="flex flex-col justify-center divide-y divide-slate-200 [&>*]:py-16">
            <div class="w-full max-w-3xl mx-auto">
                <?php foreach ($timelineItems as $item): ?>
                    <div class="relative pl-8 sm:pl-32 py-6 group">
                        <div class="font-caveat font-medium text-2xl text-indigo-500 mb-1 sm:mb-0"><?php echo $item->label; ?></div>
                        <div class="flex flex-col sm:flex-row items-start mb-1 group-last:before:hidden before:absolute before:left-2 sm:before:left-0 before:h-full before:px-px before:bg-slate-300 sm:before:ml-[6.5rem] before:self-start before:-translate-x-1/2 before:translate-y-3 after:absolute after:left-2 sm:after:left-0 after:w-2 after:h-2 after:bg-indigo-600 after:border-4 after:box-content after:border-slate-50 after:rounded-full sm:after:ml-[6.5rem] after:-translate-x-1/2 after:translate-y-1.5">
                            <time class="sm:absolute left-0 translate-y-0.5 inline-flex items-center justify-center text-xs font-semibold uppercase w-20 h-6 mb-3 sm:mb-0 rounded-full"
                                  style="background-color: <?php echo $item->couleur; ?>;">
                                <?php echo $item->loan_date; ?>
                            </time>
                            <div class="text-xl font-bold text-slate-900"><?php echo $item->titre; ?></div>
                        </div>
                        <div class="text-slate-500"><?php echo ($item->quantite > 1) ? $item->quantite . ' exemplaire(s)' : ''; ?></div>
                        <?php if (!empty($item->btnlink)): ?>
                            <form method="post" action="/historique/cancel" id="myForm">
                                <input type="hidden" name="book_id" value="<?php echo $item->id_book; ?>">
                                <input type="hidden" name="user_id" value="<?php echo $item->id_user; ?>">
                                <input type="hidden" name="loanReturn" value="<?php echo $item->loan_date; ?>">
                                <div class="flex items-center mt-4">
                                    <button type="submit" href="<?php echo $item->btnlink; ?>"
                                            class="bg-red-500 text-white px-10 py-2 rounded-full cursor-pointer inline-block">
                                        <?php echo $item->btnlink; ?>
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>
