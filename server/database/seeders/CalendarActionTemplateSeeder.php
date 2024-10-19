<?php

namespace Database\Seeders;

use App\Helpers\Enums\CalendarActionTagEnum;
use App\Models\CalendarActionTag;
use App\Models\CalendarActionTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalendarActionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cashFlowTag = CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::CASH_FLOW->value,
        ]);

        $shortTermLoanTag = CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::SHORT_TERM_LOAN->value,
        ]);

        $longTermLoanTag = CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::LONG_TERM_LOAN->value,
        ]);

        $paymentTag = CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::PAYMENT->value,
        ]);

        $technicalTag = CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::TECHNICAL->value,
        ]);

        $otherTag = CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::OTHER->value,
        ]);

        $paymentTemplate = CalendarActionTemplate::create([
            'title' => 'Future payment plan',
            'description' => 'Discuss future payment plan.',
        ]);
        $paymentTemplate->calendarActionTags()->attach([
            $paymentTag->id, $shortTermLoanTag->id, $longTermLoanTag->id,
        ]);

        $cashFlowTemplate = CalendarActionTemplate::create([
            'title' => 'Monthly Cash Flow Review',
            'description' => 'Review the monthly cash flow and financial status.',
        ]);
        $cashFlowTemplate->calendarActionTags()->attach([
            $cashFlowTag->id, $technicalTag->id,
        ]);

        $loanApplicationTemplate = CalendarActionTemplate::create([
            'title' => 'Short Term Loan Application',
            'description' => 'Discuss and prepare documents for short term loan application.',
        ]);
        $loanApplicationTemplate->calendarActionTags()->attach([
            $shortTermLoanTag->id, $technicalTag->id,
        ]);

        $longTermLoanReviewTemplate = CalendarActionTemplate::create([
            'title' => 'Long Term Loan Review',
            'description' => 'Review the status and terms of long term loans.',
        ]);
        $longTermLoanReviewTemplate->calendarActionTags()->attach([
            $longTermLoanTag->id, $technicalTag->id,
        ]);

        $technicalMeetingTemplate = CalendarActionTemplate::create([
            'title' => 'Technical Meeting',
            'description' => 'Discuss technical aspects and issues.',
        ]);
        $technicalMeetingTemplate->calendarActionTags()->attach([
            $technicalTag->id, $otherTag->id,
        ]);
    }
}
