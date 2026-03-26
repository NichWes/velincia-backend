<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class CompanyProfileController extends Controller {
    public function home() {
        return view('company.home');
    }

    public function products() {
        return view('company.products');
    }

    public function about() {
        return view('company.about');
    }

    public function contact() {
        return view('company.contact');
    }
}