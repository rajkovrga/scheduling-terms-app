<?php
declare(strict_types=1);
namespace SchedulingTerms\App\Dto\Terms;

use Carbon\CarbonImmutable;
use SchedulingTerms\App\Dto\BaseDto;

class CreateUpdateTermDto extends BaseDto
{
    private int $userId;
    private ?CarbonImmutable $startDate;
    private ?CarbonImmutable $endDate;
    private int $jobId;
    private int $companyId;
    public static function from(array $data): CreateUpdateTermDto
    {
        $current = new self($data);
        
        $current->companyId = $data['company_id'];
        $current->jobId = $data['job_id'];
        $current->userId = $data['user_id'];
        $current->startDate = $current->parseDateTime($data['start_date']);
        $current->endDate = $current->parseDateTime($data['end_date']);
    
        return $current;
    }
    
}