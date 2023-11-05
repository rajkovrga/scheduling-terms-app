<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

interface TermsRepositoryContract extends RepositoryContract
{
    public function calculateTerms();
}