<?php

namespace SchedulingTerms\App\Contracts\Repositories;

interface TermsRepositoryContract extends RepositoryContract
{
    public function calculateTerms();
}