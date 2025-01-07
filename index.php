<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Tools/Autoload.php';

use BibliOlen\Autoload;
use BibliOlen\Middlewares\AuthMiddleware;
use BibliOlen\Middlewares\ManagerAuthApiMiddleware;
use BibliOlen\Middlewares\ManagerAuthMiddleware;
use BibliOlen\Tools\Env;
use BibliOlen\Controllers;
use Corviz\Router\Facade\RouterFacade as Router;

Autoload::register();
Env::loadEnv();
session_start();

Router::get('/', function () {
    header("Location: /accueil");
});

Router::get('/accueil', [Controllers\HomeController::class, 'show'])->middleware(AuthMiddleware::class);

Router::prefix('/historique')->group(function () {
    Router::get('', [Controllers\HistoryController::class, 'show'])->middleware(AuthMiddleware::class); // historique
    Router::post('/cancel', [Controllers\HistoryController::class, 'cancel']);
});

Router::get('/reservation', [Controllers\LoanController::class, 'list'])->middleware(ManagerAuthMiddleware::class); // reservation

Router::get('/login', [Controllers\LoginController::class, 'render']);
Router::post('/login', [Controllers\LoginController::class, 'handle']);

Router::get('logout', [Controllers\LogoutController::class, 'handle']); // logout

Router::prefix('/forgot-password')->group(function () {
    Router::get('', [Controllers\ForgotPasswordController::class, 'render']);
    Router::post('', [Controllers\ForgotPasswordController::class, 'handle']);
    Router::get('(.*?)', [Controllers\ForgotPasswordController::class, 'renderWithToken']);
    Router::post('(.*?)', [Controllers\ForgotPasswordController::class, 'handleWithToken']);
});

Router::prefix('/livres')->group(function () {
    Router::get('recherche', [Controllers\BooksController::class, 'index'])->middleware(AuthMiddleware::class); // /livres/recherche
    Router::get('(\d+)', [Controllers\BooksController::class, 'show'])->middleware(AuthMiddleware::class); // /livres/id
    Router::post('/loan', [Controllers\LoanController::class, 'add']);
});

Router::prefix('/inventaire')->group(function () {
    Router::get('', [Controllers\InventoryController::class, 'inventory'])->middleware(ManagerAuthMiddleware::class); // /livres/recherche
    Router::get('/ajout', [Controllers\InventoryController::class, 'add'])->middleware(ManagerAuthMiddleware::class); // /livres/id
    Router::post('/ajoutLivreInventaire', [Controllers\InventoryController::class, 'addBooksInventory']);
    Router::post('/ajoutMotCle', [Controllers\InventoryController::class, 'addkeyworld']);
});
Router::get('/profile', [Controllers\ProfileController::class, 'render'])->middleware(ManagerAuthMiddleware::class); // Info user
Router::post('/profile', [Controllers\ProfileController::class, 'handle'])->middleware(ManagerAuthMiddleware::class); // Info user

Router::prefix('/utilisateur')->group(function () { //
    Router::get('/recherche', [Controllers\UserController::class, 'index'])->middleware(ManagerAuthMiddleware::class); // Informations Listée
    Router::get('/details', [Controllers\UserController::class, 'info'])->middleware(AuthMiddleware::class); // Information utilisateurs
    Router::get('/ajout', [Controllers\UserController::class, 'add'])->middleware(ManagerAuthMiddleware::class); // Accédé page ajout utilisateur
    Router::post('/ajoutUtilisateur', [Controllers\UserController::class, 'addUtilisateur']);
});

// region API

Router::prefix('/api')->group(function () {
    Router::get('/keywords', [Controllers\ApiController::class, 'getKeywords'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/keyword', [Controllers\ApiController::class, 'addKeyword'])->middleware(ManagerAuthApiMiddleware::class);
    Router::get('/authors', [Controllers\ApiController::class, 'getAuthors'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/author', [Controllers\ApiController::class, 'addAuthor'])->middleware(ManagerAuthApiMiddleware::class);
    Router::get('/publishers', [Controllers\ApiController::class, 'getPublishers'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/publisher', [Controllers\ApiController::class, 'addPublisher'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/book', [Controllers\ApiController::class, 'modifBook'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/user', [Controllers\ApiController::class, 'modifUser'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/loanFirstStep', [Controllers\ApiController::class, 'loanFirstStep'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/loanSecondStep', [Controllers\ApiController::class, 'loanReturn'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/deletebook', [Controllers\ApiController::class, 'deleteBook'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/archivuser', [Controllers\ApiController::class, 'archivUser'])->middleware(ManagerAuthApiMiddleware::class);
    Router::post('/cancelLoan', [Controllers\ApiController::class, 'cancelLoan'])->middleware(ManagerAuthApiMiddleware::class);

});


// endregion

// region Errors
Router::get('/not-allowed', [Controllers\ErrorsController::class, 'notAllowed']);
Router::get('/not-found', [Controllers\ErrorsController::class, 'notFound']);
// endregion


echo Router::dispatch();

if (!Router::found()) {
    header("Location: /not-found");
    exit();
}
