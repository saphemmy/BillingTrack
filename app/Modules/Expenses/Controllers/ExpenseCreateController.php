<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace FI\Modules\Expenses\Controllers;

use FI\Http\Controllers\Controller;
use FI\Modules\Categories\Models\Category;
use FI\Modules\CompanyProfiles\Models\CompanyProfile;
use FI\Modules\CustomFields\Models\CustomField;
use FI\Modules\Expenses\Models\Expense;
use FI\Modules\Expenses\Requests\ExpenseRequest;
use FI\Modules\Vendors\Models\Vendor;
use FI\Support\DateFormatter;
use FI\Support\NumberFormatter;
use FI\Traits\ReturnUrl;

class ExpenseCreateController extends Controller
{
    use ReturnUrl;

    public function create()
    {
        return view('expenses.form')
            ->with('editMode', false)
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('categories', Category::pluck('name', 'id'))
            ->with('vendors', Vendor::pluck('name', 'id'))
            ->with('currentDate', DateFormatter::format(date('Y-m-d')))
            ->with('customFields', CustomField::forTable('expenses')->get());
    }

    public function store(ExpenseRequest $request)
    {
        $record = request()->except('attachments', 'custom');

        $record['expense_date'] = DateFormatter::unformat($record['expense_date']);
        $record['amount'] = NumberFormatter::unformat($record['amount']);
        $record['tax'] = ($record['tax']) ? NumberFormatter::unformat($record['tax']) : 0;

        $expense = Expense::create($record);

        $expense->custom->update(request('custom', []));

        return redirect($this->getReturnUrl())
            ->with('alertSuccess', trans('fi.record_successfully_created'));
    }
}
