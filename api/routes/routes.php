
<?php
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

/** @var App $app */

$this->app->group(
    '/terms',
    function (RouteCollectorProxy $grp) {
        $grp->get('/', ['SchedulingTerms\App\Controllers\TermController', 'getTerms']);
        $grp->get('/{id}', ['SchedulingTerms\App\Controllers\TermController', 'getTerm']);
        $grp->post('/', ['SchedulingTerms\App\Controllers\TermController', 'createTerm']);
        $grp->delete('/{id}', ['SchedulingTerms\App\Controllers\TermController', 'deleteTerm']);
        $grp->put('/{id}', ['SchedulingTerms\App\Controllers\TermController', 'editTerm']);
    }
);
$this->app->post('/login', ['SchedulingTerms\App\Controllers\AuthController', 'login'])->add(SchedulingTerms\App\Middlewares\AuthMiddleware::class);
$this->app->post('/register', ['SchedulingTerms\App\Controllers\AuthController', 'register']);
$this->app->get('/me', ['SchedulingTerms\App\Controllers\AuthController', 'me']);
$this->app->post('/forget-password/{token}', ['SchedulingTerms\App\Controllers\AuthController', 'forgetPassword']);
$this->app->put('/change-password', ['SchedulingTerms\App\Controllers\AuthController', 'changePassword']);
$this->app->group(
    '/companies',
    function (RouteCollectorProxy $grp) {
        $grp->get('/', ['SchedulingTerms\App\Controllers\CompanyController', 'getCompanies']);
        $grp->get('{id}', ['SchedulingTerms\App\Controllers\CompanyController', 'getCompany']);
        $grp->post('/', ['SchedulingTerms\App\Controllers\CompanyController', 'createCompany']);
        $grp->delete('{id}', ['SchedulingTerms\App\Controllers\CompanyController', 'deleteCompany']);
        $grp->put('{id}', ['SchedulingTerms\App\Controllers\CompanyController', 'editCompany']);
    }
);
$this->app->group(
    '/users',
    function (RouteCollectorProxy $grp) {
        $grp->get('/', ['SchedulingTerms\App\Controllers\UserController', 'getUsers']);
        $grp->get('/{id}', ['SchedulingTerms\App\Controllers\UserController', 'getUser']);
        $grp->post('/', ['SchedulingTerms\App\Controllers\UserController', 'createUser']);
        $grp->delete('/{id}', ['SchedulingTerms\App\Controllers\UserController', 'deleteUser']);
        $grp->put('/{id}', ['SchedulingTerms\App\Controllers\UserController', 'editUser']);
    }
);
$this->app->group(
    '/jobs',
    function (RouteCollectorProxy $grp) {
        $grp->get('/', ['SchedulingTerms\App\Controllers\JobController', 'getJobs'])->add(SchedulingTerms\App\Middlewares\AuthMiddleware::class);
        $grp->get('/{id}', ['SchedulingTerms\App\Controllers\JobController', 'getJob']);
        $grp->post('/', ['SchedulingTerms\App\Controllers\JobController', 'createJob']);
        $grp->delete('/{id}', ['SchedulingTerms\App\Controllers\JobController', 'deleteJob']);
        $grp->put('/{id}', ['SchedulingTerms\App\Controllers\JobController', 'editJob']);
    }
)->add(SchedulingTerms\App\Middlewares\AuthMiddleware::class);
