<?php

Route::Get("", "Home", "Home", 1);
Route::Get("/about-us", "Home", "AboutUs", 1);
Route::Get("/contact-us", "Home", "ContactUs", 1);
Route::Get("/faq", "Home", "FAQ", 1);
Route::Get("/job-openings", "Home", "JobOpenings", 1);
Route::Get("/our-services", "Home", "OurServices", 1);
Route::Get("/our-team", "Home", "OurTeam", 1);
Route::Get("/why-us", "Home", "WhyUs", 1);

Route::Post("/api/contact-us", "Home", "ContactUsApi", 1);








//Route::Get("/login", "User", "Login", 1);
//
//Route::Get("/pages", "Home", "Pages", 1);
//
//
//
//
//Route::Post("/api/save-page", "Home", "PageSaveApi");
//Route::Post("/api/delete-page", "Home", "PageDeleteApi");
//
//Route::Post("/api/login", "User", "LoginApi", 1);
//Route::Post("/api/logout", "User", "LogoutApi", 1);
//Route::Post("/api/400", "Home", "NotAuthorized");

//Route::Get("/edit/{url}", "Home", "Edit");
//Route::Get("/{url}", "Home", "Page", 1);
