<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Contracts\Repositories;

use SchedulingTerms\App\Dto\Companies\CreateUpdateCompanyDto;
use SchedulingTerms\App\Models\Company;

interface CompanyRepositoryContract extends RepositoryContract
{
    public function create(CreateUpdateCompanyDto $companyDto): Company;
    public function update(int $id, CreateUpdateCompanyDto $companyDto): Company;
    
}