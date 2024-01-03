<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{

    public function getFilters()
    {
        return [
            new TwigFilter('date_fr', [$this, 'formaterDateFr'])
        ];

    }

    // Function to transform english days and months into french days and months. The first parameter will be a string with the format that we want for the date the second one will be the actual date
    public function formaterDateFr($date, $format){

        // We check if the data entered is a dateTime
        if ($date instanceof \DateTime) {

            // $date is a DateTime object, we format it directly
            $timestamp = $date->getTimestamp();

        // or if we get an integer
        } elseif (is_numeric($date)) {

            // we dont need to modify it
            $timestamp = $date;

        // or if we get a string
        } else {

            // create a DateTime object
            $dateTime = new \DateTime($date);
            $timestamp = $dateTime->getTimestamp();
        }


        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, $timestamp)));
        
    }

}