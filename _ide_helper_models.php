<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $branch_id
 * @property string $account_number
 * @property string $account_name
 * @property float $balance
 * @property string|null $category
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $incomingTransfers
 * @property-read int|null $incoming_transfers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transfer> $outgoingTransfers
 * @property-read int|null $outgoing_transfers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Account newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Account query()
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Account whereUpdatedAt($value)
 */
	class Account extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $name
 * @property string $branch_code
 * @property string|null $phone
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BranchAccount> $branchAccounts
 * @property-read int|null $branch_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Center> $centers
 * @property-read int|null $centers_count
 * @property-read \App\Models\User|null $creator
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereBranchCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Branch whereUserId($value)
 */
	class Branch extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyCashSummary> $dailyCashSummaries
 * @property-read int|null $daily_cash_summaries_count
 * @method static \Illuminate\Database\Eloquent\Builder|BranchAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BranchAccount query()
 */
	class BranchAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $daily_cash_summary_id
 * @property float $value
 * @property float $count
 * @property int $is_coin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\DailyCashSummary|null $dailyCashSummary
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination query()
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereDailyCashSummaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereIsCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CashDenomination whereValue($value)
 */
	class CashDenomination extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DailyCashSummary> $dailyCashSummaries
 * @property-read int|null $daily_cash_summaries_count
 * @method static \Illuminate\Database\Eloquent\Builder|CashierAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashierAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CashierAccount query()
 */
	class CashierAccount extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $branch_id
 * @property string $center_code
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @method static \Illuminate\Database\Eloquent\Builder|Center newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Center newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Center query()
 * @method static \Illuminate\Database\Eloquent\Builder|Center whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Center whereCenterCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Center whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Center whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Center whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Center whereUpdatedAt($value)
 */
	class Center extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|City query()
 * @method static \Illuminate\Database\Eloquent\Builder|City whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|City whereUpdatedAt($value)
 */
	class City extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $loan_id
 * @property int $collector_id
 * @property string $collected_amount
 * @property string $principal_amount
 * @property string $interest_amount
 * @property \Illuminate\Support\Carbon $collection_date
 * @property string $collection_method
 * @property string $penalty_collected
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $collector
 * @property-read \App\Models\Loan|null $loan
 * @property-read \App\Models\User|null $staff
 * @property-read \App\Models\StaffCollectionStatus|null $staffCollectionStatus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\Transfer|null $transfer
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection query()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectionMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCollectorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereInterestAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePenaltyCollected($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection wherePrincipalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Collection withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Collection withoutTrashed()
 */
	class Collection extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $invoice_number
 * @property int $loan_id
 * @property int $collection_id
 * @property string $collected_amount
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Collection|null $collection
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice query()
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereCollectedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereInvoiceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CollectionInvoice whereUpdatedAt($value)
 */
	class CollectionInvoice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $logo
 * @property string $capital_balance
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string|null $website
 * @property string|null $registration_no
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Company query()
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCapitalBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereRegistrationNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Company whereWebsite($value)
 */
	class Company extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $center_id
 * @property string $full_name
 * @property string $customer_no
 * @property string $nic
 * @property string $permanent_address
 * @property string|null $living_address
 * @property string|null $permanent_city
 * @property string|null $living_city
 * @property string $customer_phone
 * @property string $date_of_birth
 * @property string $occupation
 * @property string $gender
 * @property string $civil_status
 * @property string|null $spouse_name
 * @property string|null $spouse_nic
 * @property string|null $Spouse_phone
 * @property string|null $spouse_occupation
 * @property string|null $spouse_age
 * @property string|null $home_phone
 * @property string|null $family_members
 * @property string|null $income_earners
 * @property string|null $family_income
 * @property string|null $photo
 * @property string|null $nic_copy
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Center|null $center
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\GroupMember> $groupMembers
 * @property-read int|null $group_members_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanGuarantor> $loanGuarantors
 * @property-read int|null $loan_guarantors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Type> $types
 * @property-read int|null $types_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer excludeQuarter()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer filterByRoleAndBranch($user)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer search($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCivilStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCustomerNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCustomerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereDateOfBirth($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereFamilyIncome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereFamilyMembers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereHomePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIncomeEarners($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLivingAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereLivingCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereNic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereNicCopy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePermanentAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePermanentCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereSpouseAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereSpouseName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereSpouseNic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereSpouseOccupation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereSpousePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyBalance query()
 */
	class DailyBalance extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $branch_id
 * @property int $account_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $counted_amount
 * @property string $system_amount
 * @property string $difference
 * @property string|null $remarks
 * @property int $counted_by
 * @property int|null $verified_by
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $countedBy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CashDenomination> $denominations
 * @property-read int|null $denominations_count
 * @property-read \App\Models\User|null $verifiedBy
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary query()
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereCountedAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereCountedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereDifference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereRemarks($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereSystemAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DailyCashSummary whereVerifiedBy($value)
 */
	class DailyCashSummary extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $group_code
 * @property int|null $group_leader
 * @property int $center_id
 * @property int $creator_id
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\Center|null $center
 * @property-read \App\Models\Customer|null $leader
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $members
 * @property-read int|null $members_count
 * @method static \Illuminate\Database\Eloquent\Builder|Group filterByRoleAndBranch($user)
 * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Group query()
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereGroupCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereGroupLeader($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
 */
	class Group extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $group_id
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Group|null $group
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember query()
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|GroupMember whereUpdatedAt($value)
 */
	class GroupMember extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $loan_number
 * @property string $loan_type
 * @property int|null $customer_id
 * @property int|null $group_id
 * @property int $center_id
 * @property int $scheme_id
 * @property string $loan_amount
 * @property string|null $document_charge
 * @property \Illuminate\Support\Carbon|null $start_date
 * @property int $loan_creator_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\LoanApproval|null $approval
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Center|null $center
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanCollectionSchedule> $collections
 * @property-read int|null $collections_count
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Group|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $guarantors
 * @property-read int|null $guarantors_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LoanCollectionSchedule> $loanCollectionSchedules
 * @property-read int|null $loan_collection_schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Collection> $loanCollections
 * @property-read int|null $loan_collections_count
 * @property-read \App\Models\LoanProgress|null $loanProgress
 * @property-read \App\Models\LoanScheme|null $loanScheme
 * @property-read \App\Models\Voucher|null $voucher
 * @method static \Illuminate\Database\Eloquent\Builder|Loan active()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan branchFilter($branchId)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan query()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCenterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereDocumentCharge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanCreatorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereLoanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereSchemeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Loan withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Loan withoutTrashed()
 */
	class Loan extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $loan_id
 * @property string $status
 * @property int|null $approved_by
 * @property string|null $approved_at
 * @property string|null $active_at
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereActiveAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanApproval whereUpdatedAt($value)
 */
	class LoanApproval extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $loan_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $description
 * @property string|null $principal
 * @property string|null $interest
 * @property string|null $penalty
 * @property string $due
 * @property string|null $paid
 * @property string|null $pending_due
 * @property string $total_due
 * @property string|null $principal_due
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereInterest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule wherePenalty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule wherePendingDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule wherePrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule wherePrincipalDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereTotalDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanCollectionSchedule whereUpdatedAt($value)
 */
	class LoanCollectionSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $loan_id
 * @property int $customer_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $guaranteedLoans
 * @property-read int|null $guaranteed_loans_count
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanGuarantor whereUpdatedAt($value)
 */
	class LoanGuarantor extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $loan_id
 * @property string $total_amount
 * @property string $balance
 * @property string $total_paid_amount
 * @property string|null $last_due_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $status
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereLastDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereTotalPaidAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanProgress whereUpdatedAt($value)
 */
	class LoanProgress extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $loan_name
 * @property string $loan_type
 * @property string $interest_rate
 * @property string $collecting_duration
 * @property int $loan_term
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Loan> $loans
 * @property-read int|null $loans_count
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme query()
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereCollectingDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereInterestRate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereLoanName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereLoanTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereLoanType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LoanScheme whereUpdatedAt($value)
 */
	class LoanScheme extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $message
 * @property string|null $read_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereReadAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUserId($value)
 */
	class Notification extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $payment_category_id
 * @property int $branch_id
 * @property string $total_amount
 * @property string $status
 * @property string|null $rejection_reason
 * @property string|null $attachments
 * @property int $created_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\PaymentCategory|null $paymentCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentSupplier> $suppliers
 * @property-read int|null $suppliers_count
 * @property-read \App\Models\Voucher|null $voucher
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment wherePaymentCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Payment whereUpdatedAt($value)
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PaymentSupplier> $suppliers
 * @property-read int|null $suppliers_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentCategory whereUpdatedAt($value)
 */
	class PaymentCategory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $payment_category_id
 * @property string $name
 * @property string|null $nic
 * @property string|null $salary
 * @property string|null $bank_account_name
 * @property string|null $bank_account_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\PaymentCategory|null $paymentCategory
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereBankAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereBankAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereNic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier wherePaymentCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereSalary($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplier whereUpdatedAt($value)
 */
	class PaymentSupplier extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $payment_id
 * @property int $supplier_id
 * @property string $amount
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplierPivot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplierPivot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplierPivot query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplierPivot whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplierPivot wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentSupplierPivot whereSupplierId($value)
 */
	class PaymentSupplierPivot extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PermissionGroup whereUpdatedAt($value)
 */
	class PermissionGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $branch_id
 * @property int $account_id
 * @property string $amount
 * @property int $type_id
 * @property string $status
 * @property string|null $rejection_reason
 * @property int $request_employee
 * @property int $created_by
 * @property int|null $approved_by
 * @property string|null $attachments
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\User|null $requestEmployee
 * @property-read \App\Models\PettyCashType|null $type
 * @property-read \App\Models\Voucher|null $voucher
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereAttachments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereRequestEmployee($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashRequest whereUpdatedAt($value)
 */
	class PettyCashRequest extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PettyCashRequest> $pettyCashRequests
 * @property-read int|null $petty_cash_requests_count
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashType query()
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashType whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PettyCashType whereUpdatedAt($value)
 */
	class PettyCashType extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $collection_id
 * @property int|null $transfers_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Collection|null $collection
 * @property-read \App\Models\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus whereTransfersId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StaffCollectionStatus whereUpdatedAt($value)
 */
	class StaffCollectionStatus extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $debit_account_id
 * @property int $credit_account_id
 * @property string $amount
 * @property int|null $loan_id
 * @property int|null $customer_id
 * @property int|null $collection_id
 * @property int|null $branch_id
 * @property string $transaction_date
 * @property string $transaction_type
 * @property string|null $description
 * @property string $status
 * @property int $created_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\Account|null $creditAccount
 * @property-read \App\Models\Account|null $debitAccount
 * @property-read \App\Models\Transfer|null $transfer
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction pettyCash()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCollectionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreditAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDebitAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $branch_id
 * @property int|null $from_account_id
 * @property int $to_account_id
 * @property string $amount
 * @property string|null $rejection_reason
 * @property string|null $description
 * @property int|null $collector_id
 * @property string $status
 * @property int $created_by
 * @property int|null $approved_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $approvedBy
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $createdBy
 * @property-read \App\Models\Account|null $fromAccount
 * @property-read \App\Models\Account|null $toAccount
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereCollectorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereFromAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereToAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transfer whereUpdatedAt($value)
 */
	class Transfer extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Type newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Type query()
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Type whereUpdatedAt($value)
 */
	class Type extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string $status
 * @property string|null $remember_token
 * @property int|null $current_team_id
 * @property string|null $profile_photo_path
 * @property string|null $last_seen_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $branch_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $approvedTransactions
 * @property-read int|null $approved_transactions_count
 * @property-read \App\Models\Branch|null $branch
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Collection> $collections
 * @property-read int|null $collections_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $createdTransactions
 * @property-read int|null $created_transactions_count
 * @property-read \App\Models\UserDetails|null $details
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $employee_id
 * @property string $nic_no
 * @property string|null $profile_photo
 * @property string|null $address
 * @property string|null $phone_number
 * @property string|null $gender
 * @property int|null $age
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereAge($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereEmployeeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereNicNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereProfilePhoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserDetails whereUserId($value)
 */
	class UserDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $voucher_number
 * @property string $type
 * @property int|null $reference_id
 * @property \Illuminate\Support\Carbon $date
 * @property string $amount
 * @property string|null $payee_details
 * @property string|null $description
 * @property int|null $account_id
 * @property int|null $loan_id
 * @property int|null $customer_id
 * @property int $approved_by
 * @property int $created_by
 * @property int $branch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Account|null $account
 * @property-read \App\Models\User|null $approver
 * @property-read \App\Models\Branch|null $branch
 * @property-read \App\Models\User|null $creator
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Loan|null $loan
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereApprovedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereBranchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereLoanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher wherePayeeDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereReferenceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereVoucherNumber($value)
 */
	class Voucher extends \Eloquent {}
}

