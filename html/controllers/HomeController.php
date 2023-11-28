<?php

class HomeController {

    public static function Home() {
        //Repository::Requires("PageRepository");
        $response = [
            "title" => "Home",
            "image" => "Home-meta.jpeg",
            "banner" => "pexels-jeshootscom-442574-2000x1333.jpg"
        ];
        return View::Render($response, "home.phtml");
    }

    public static function AboutUs() {
        $response = [
            "title" => "About Us",
            "image" => "About Us-meta.jpeg",
            "banner" => "pexels-alexas-fotos-2277784-2000x1333.jpg"
        ];
        return View::Render($response, "about_us.phtml");
    }

    public static function ContactUs() {
        $response = [
            "title" => "Contact Us",
            "image" => "Contact Us-meta.jpeg",
            "banner" => "pexels-picjumbocom-461077-2000x1334.jpg"
        ];
        return View::Render($response, "contact_us.phtml", ["contact"]);
    }

    public static function WhyUs(&$response) {
        $response = [
            "title" => "Why choosing Us",
            "image" => "Why choosing Us-meta.jpeg",
            "banner" => "pexels-ylanite-koppens-843266-2000x1331.jpg"
        ];
        return View::Render($response, "why_us.phtml");
    }

    public static function FAQ(&$response) {
        $response = [
            "title" => "FAQ",
            "image" => "FAQ-meta.jpeg",
            "banner" => "pexels-pixabay-221164-1920x1280.jpeg"
        ];
        return View::Render($response, "faq.phtml");
    }

    public static function JobOpenings(&$response) {
        $response = [
            "title" => "Job Opening",
            "image" => "Job Openings-meta.jpeg",
            "banner" => "pexels-fernando-arcos-211122-2000x1330.jpg"
        ];
        return View::Render($response, "job_openings.phtml");
    }

    public static function OurServices(&$response) {
        $response = [
            "title" => "Our Services",
            "image" => "Our Services-meta.jpeg",
            "banner" => "pexels-serpstat-572056-2000x1334.jpg"
        ];
        return View::Render($response, "our_services.phtml");
    }

    public static function OurTeam(&$response) {
        $response = [
            "title" => "Our Team",
            "image" => "Our Team-meta.jpeg",
            "banner" => "pexels-life-of-pix-7974-2000x1333.jpg"
        ];
        return View::Render($response, "our_team.phtml");
    }

    public static function ContactUsApi($post) {
        $response = new APIResult();
        $response->title = "Contact Us";
        $response->message = "We shall review your query and get back to you shortly.";

        $contact = [];
        $to = "info@e-mersoutsourcing.com";
        $subject = "Contact Us Form";
        $headers = "From: info@e-mersoutsourcing.com";

        if ($post->email) {
            $contact[] = $post->email;
            $txt = "Hi $post->name,\n\nWe've received your email request – thanks for reaching out!\n\nIf it's within normal business hours, we'll get back to you as soon as possible (9am-5pm MUT Monday-Friday, excluding public holidays).\n\nWe value our evenings and weekends and know that you probably do too, so if it's outside of that range, we'll get back to you within 24 business hours.\n\nTalk soon,\n\n~E-mers Outsourcing Team";
            mail($post->email, $subject, $txt, $headers);
        }
        if ($post->phone) {
            $contact[] = $post->phone;
        }
        $txt = "Message From:  $post->name ( " . implode("|", $contact) . ")\n" . $post->message;
        mail($to, $subject, $txt, $headers);

        $response->payload = $post;
        return $response;
    }

//    
//    public static function Pages(&$template, &$response) {
//        Repository::Requires("PageRepository");
//        $template = "list.phtml";
//        $response = [
//            "pages" => PageRepository::Get()
//        ];
//    }
//
//    public static function Page(&$template, &$response, $params) {
//        Repository::Requires("PageRepository");
//        $template = "layout.phtml";
//        $response = [
//            "page" => PageRepository::GetByUrl($params["url"])
//        ];
//    }
//
//    public static function Edit(&$template, &$response, $params) {
//        Repository::Requires("PageRepository");
//        $user = UserRepository::GetCurrent();
//        $response = [
//            "url" => $params["url"],
//            "page" => PageRepository::GetByUrl($params["url"])
//        ];
//        $template = $user->admin ? "edit.phtml" : "layout.phtml";
//    }
//
//    public static function PageApi(&$response, $post) {
//        Repository::Requires("PageRepository");
//        $page = PageRepository::GetByUrl($post->url);
//        $response->status = "success";
//        $response->payload = $page;
//    }
//
//    public static function PageSaveApi(&$response, $post) {
//        Repository::Requires("PageRepository");
//        PageRepository::SetByUrl($post->url, $post);
//        $response->title = "Saved";
//        $response->status = "success";
//        $response->redirect = "/" . $post->url;
//        $response->message = "Page saved successfully.";
//    }
//
//    public static function PageDeleteApi(&$response, $post) {
//        Repository::Requires("PageRepository");
//        PageRepository::DeleteByUrl($post->url);
//        $response->title = "Deleted";
//        $response->status = "success";
//        $response->redirect = "/pages";
//        $response->message = "Page deleted successfully.";
//    }
//
//    public static function NotAuthorized(&$response, $post, $args) {
//        $response->title = "Error";
//        $response->message = "You are not authorized to view this page.";
//        $response->payload = false;
//        $response->status = "error";
//    }
}
