<?php

use Illuminate\Database\Seeder;
use App\Expense;

class ExpensesTableSeeder extends Seeder
{
    const EXPENSES = [
        [
            'name' => "Blog",
            'price' => 70.00,
        ],
        [
            'name' => "Google Workspace",
            'price' => 1500.00,
        ],
        [
            'name' => "Hosting",
            'price' => 2500.00,
        ],
        [
            'name' => "SSL",
            'price' => 0.00,
        ],
        [
            'name' => "Yext Expense",
            'price' => 5700.00,
        ],
        [
            'name' => "Content Writing Expense",
            'price' => 1000.00,
        ],
        [
            'name' => "Front End Developer Expense",
            'price' => 3000.00,
        ],
        [
            'name' => "Graphic Design Expense",
            'price' => 2000.00,
        ],
        [
            'name' => "Programming Sub-Contractor",
            'price' => 5200.00,
        ],
        [
            'name' => "Phone/Internet",
            'price' => 300.00,
        ],
        [
            'name' => "LinkedIn",
            'price' => 50.00,
        ],
        [
            'name' => "Credit Card Fees",
            'price' => 1500.00,
        ],
        [
            'name' => "Commissions",
            'price' => 2100.00,
        ],
        [
            'name' => "Development/Training",
            'price' => 100.00,
        ],
        [
            'name' => "Adobe",
            'price' => 65.00,
        ],
        [
            'name' => "BugSnag",
            'price' => 29.00,
        ],
        [
            'name' => "Mailgun",
            'price' => 151.00,
        ],
        [
            'name' => "QuickBooks",
            'price' => 44.00,
        ],
        [
            'name' => "Zapier",
            'price' => 50.00,
        ],
        [
            'name' => "XML Sitemaps",
            'price' => 100.00,
        ],
        [
            'name' => "Office Supplies/Computers",
            'price' => 500.00,
        ],
        [
            'name' => "Professional Fees",
            'price' => 500.00,
        ],
        [
            'name' => "Domains",
            'price' => 30.00,
        ],
        [
            'name' => "DontGo",
            'price' => 198.00,
        ],
        [
            'name' => "OrderSnapp",
            'price' => 237.00,
        ],
        [
            'name' => "Disability/Insurances",
            'price' => 200.00,
        ],
        [
            'name' => "Bookkeeping",
            'price' => 500.00,
        ],
        [
            'name' => "Github",
            'price' => 50.00,
        ],
        [
            'name' => "Print Materials",
            'price' => 30.00,
        ],
        [
            'name' => "Invoice Ninja",
            'price' => 50.00,
        ],
        [
            'name' => "Elfisight",
            'price' => 60.00,
        ],
        [
            'name' => "Google Cloud (Voice)",
            'price' => 10.00,
        ],
        [
            'name' => "Utilities",
            'price' => 220.00,
        ],
        [
            'name' => "Salary",
            'price' => 29000.00,
        ],
        [
            'name' => "Asana",
            'price' => 150.00,
        ],
        [
            'name' => "Digital Ocean",
            'price' => 50.00,
        ],
        [
            'name' => "Hootsuite",
            'price' => 500.00,
        ],
        [
            'name' => "Laravel Forge",
            'price' => 50.00,
        ],
        [
            'name' => "Microsoft Office",
            'price' => 10.00,
        ],
        [
            'name' => "PHPStorm",
            'price' => 10.00,
        ],
        [
            'name' => "TaxJar",
            'price' => 350.00,
        ],
        [
            'name' => "Zestful",
            'price' => 100.00,
        ],
        [
            'name' => "Payroll Expense",
            'price' => 100.00,
        ],
        [
            'name' => "Health Insurance",
            'price' => 1200.00,
        ],        
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::EXPENSES as $expense) {
            Expense::create($expense);
        }
    }
}
