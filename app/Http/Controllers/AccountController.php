<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;

class AccountController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'import_list' => 'required|string',
            'field_separator' => 'required|string|max:1',
        ]);

        $lines = explode("\n", $request->input('import_list'));
        $separator = $request->input('field_separator');
        $skipHeader = $request->has('skip_header');

        $accounts = array_map(function ($line) use ($separator) {
            $fields = explode($separator, $line);
            return [
                'full_name' => trim($fields[0] ?? ''),
                'first_name' => trim($fields[1] ?? ''),
                'last_name' => trim($fields[2] ?? ''),
                'date' => trim($fields[3] ?? ''),
                'month' => trim($fields[4] ?? ''),
                'year' => trim($fields[5] ?? ''),
                'gender' => trim($fields[6] ?? ''),
                'email' => trim($fields[7] ?? ''),
                'password' => trim($fields[8] ?? ''),
            ];
        }, $lines);

        if ($skipHeader) {
            array_shift($accounts);
        }

        $accounts = array_filter($accounts, function ($account) {
            return !empty($account['email']) &&
                !str_contains(strtolower($account['full_name']), 'fullname');
        });

        $created = 0;
        $updated = 0;

        foreach ($accounts as $data) {
            $account = Account::updateOrCreate(
                ['email' => $data['email']],
                $data
            );

            $account->wasRecentlyCreated ? $created++ : $updated++;
        }

        return redirect()->back()->with('status', "Imported $created new accounts and updated $updated.");
    }
}
