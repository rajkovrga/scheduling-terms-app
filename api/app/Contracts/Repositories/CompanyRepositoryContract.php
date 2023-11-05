<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use SchedulingTerms\App\Dto\Companies\CompanyDto;
use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Dto\Companies\UpdateCompanyDto;

interface CompanyRepositoryContract extends RepositoryContract
{
    public function create(CreateUpdateCompanyDto $companyDto): CompanyDto;
    public function update(int $id, UpdateCompanyDto $companyDto): CompanyDto;
    
}