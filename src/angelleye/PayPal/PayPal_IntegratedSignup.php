<?php

namespace angelleye\PayPal;

/**
 *  An open source PHP library written to easily work with PayPal's Adaptive Payments API
 * 	
 *  Email:  service@angelleye.com
 *  Facebook: angelleyeconsulting
 *  Twitter: angelleye
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 * @package			paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 * @link			https://github.com/angelleye/paypal-php-library/
 * @website			http://www.angelleye.com
 * @support         http://www.angelleye.com/product/premium-support/
 * @filesource
 */

/**
 * PayPal Adaptive Payments Class
 *
 * This class houses all of the Adaptive Payments specific API's.  
 *
 * @package 		paypal-php-library
 * @author			Andrew Angell <service@angelleye.com>
 */
use DOMDocument;

class PayPal_IntegratedSignup {

    private $_auth_string;
    private $_bearer_string;
    var $Sandbox = '';
    var $PathToCertKeyPEM = '';
    var $SSL = '';    
    var $LogResults = '';
    var $LogPath = '';
    var $EndPointURL = '';

    public function __construct($configArray) {
        // Append your secret to your client ID, separated by a colon (“:”). Base64-encode the resulting string.
        $this->_auth_string = base64_encode($configArray['ClientID'] . ':' . $configArray['ClientSecret']);
        if (isset($configArray['Sandbox'])) {
            $this->Sandbox = $configArray['Sandbox'];
        } else {
            $this->Sandbox = true;
        }
        
        $this->LogResults = isset($configArray['LogResults']) ? $configArray['LogResults'] : false;
        $this->LogPath = isset($configArray['LogPath']) ? $configArray['LogPath'] : '/logs/';

        if ($this->Sandbox) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            $this->EndPointURL = 'https://api.sandbox.paypal.com/v1/';
        } else {
            $this->EndPointURL = 'https://api.paypal.com/v1/';
        }
        
        $this->_currencies = array(
            "AUD" => "Australian Dollar",
            "BRL" => "Brazilian Real",
            "CAD" => "Canadian Dollar",
            "CZK" => "Czech Koruna",
            "DKK" => "Danish Krone",
            "EUR" => "Euro",
            "HKD" => "Hong Kong Dollar",
            "HUF" => "Hungarian Forint",
            "ILS" => "Israeli New Shekel",
            "JPY" => "Japanese Yen",
            "MYR" => "Malaysian Ringgit",
            "MXN" => "Mexican Peso",
            "NOK" => "Norwegian Krone",
            "NZD" => "New Zealand Dollar",
            "PHP" => "Philippine Peso",
            "PLN" => "Polish Zloty",
            "GBP" => "British Pound Sterling",
            "RUB" => "Russian Ruble",
            "SGD" => "Singapore Dollar",
            "SEK" => "Swedish Krona",
            "CHF" => "Swiss Franc",
            "TWD" => "Taiwan New Dollar",
            "THB" => "Thai Baht",
            "TRY" => "Turkish Lira",
            "USD" => "U.S. Dollar"
        );
        $this->_countries = array(
            "AL" => "Albania",
            "DZ" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BS" => "Bahamas",
            "BH" => "Bahrain",
            "BB" => "Barbados",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia-Herzegovina",
            "BW" => "Botswana",
            "VG" => "British Virgin Islands",
            "BN" => "Brunei Darussalam",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "KH" => "Cambodia",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China (domestic transactions only)",
            "C2" => "China (international transactions only)",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "HR" => "Croatia",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "CD" => "Democratic Republic of Congo",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "ER" => "Eritria",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FK" => "Falkland Islands (Malvinas)",
            "FM" => "Federated States of Micronesia",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "FR" => "France",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "GA" => "Gabon",
            "GM" => "Gambia",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea Bissau",
            "GY" => "Guyana",
            "VA" => "Holy See (Vatican City State)",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "ID" => "Indonesia",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Laos",
            "LV" => "Latvia",
            "LS" => "Lesotho",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Malta",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "YT" => "Mayotte",
            "MX" => "Mexico",
            "MN" => "Mongolia",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "NO" => "Norway",
            "OM" => "Oman",
            "PW" => "Palau",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn",
            "PL" => "Poland",
            "PT" => "Portugal",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russian Federation",
            "RW" => "Rwanda",
            "SH" => "Saint Helena",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "PM" => "Saint Pierre and Miquelon",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "ST" => "San Tome and Principe",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "KR" => "South Korea / Republic of Korea",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "TW" => "Taiwan, Province of China",
            "TJ" => "Tajikistan",
            "TH" => "Thailand",
            "TG" => "Togo",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "TZ" => "United Republic of Tanzania",
            "US" => "United States",
            "UY" => "Uruguay",
            "VU" => "Vanuatu",
            "VE" => "Venezuela",
            "VN" => "Vietnam",
            "WF" => "Wallis and Futuna",
            "YE" => "Yemen",
            "ZM" => "Zambia"
        );
        
        
        $this->_business_category = array(
            "1000" => "Arts, crafts, and collectibles",
            "1001" => "Baby",
            "1002" => "Beauty and fragrances",
            "1003" => "Books and magazines",
            "1004" => "Business to business",
            "1005" => "Clothing, accessories, and shoes",
            "1006" => "Computers, accessories, and services",
            "1007" => "Education",
            "1008" => "Electronics and telecom",
            "1009" => "Entertainment and media",
            "1010" => "Financial services and products",
            "1011" => "Food retail and service",
            "1012" => "Gifts and flowers",
            "1013" => "Government",
            "1014" => "Health and personal care",
            "1015" => "Home and garden",
            "1016" => "Nonprofit",
            "1017" => "Pets and animals",
            "1018" => "Religion and spirituality (for profit)",
            "1019" => "Retail (not elsewhere classified)",
            "1020" => "Services – other",
            "1021" => "Sports and outdoors",
            "1022" => "Toys and hobbies",
            "1023" => "Travel",
            "1024" => "Vehicle sales",
            "1025" => "Vehicle service and accessories"
        );
        
        $this->_category_sub_category_relation = array(
            "2000" => "1000",
            "2001" => "1000",
            "2002" => "1000",
            "2003" => "1000",
            "2004" => "1000",
            "2005" => "1000",
            "2006" => "1000",
            "2007" => "1000",
            "2008" => "1000",
            "2009" => "1000",
            "2010" => "1000",
            "2011" => "1001",
            "2012" => "1001",
            "2013" => "1001",
            "2014" => "1001",
            "2015" => "1002",
            "2016" => "1002",
            "2017" => "1002",
            "2018" => "1003",
            "2019" => "1003",
            "2020" => "1003",
            "2021" => "1003",
            "2022" => "1003",
            "2023" => "1003",
            "2024" => "1003",
            "2025" => "1004",
            "2026" => "1004",
            "2027" => "1004",
            "2028" => "1004",
            "2029" => "1004",
            "2030" => "1004",
            "2031" => "1004",
            "2032" => "1004",
            "2033" => "1004",
            "2034" => "1004",
            "2035" => "1004",
            "2036" => "1004",
            "2037" => "1004",
            "2038" => "1004",
            "2039" => "1004",
            "2040" => "1004",
            "2041" => "1004",
            "2042" => "1004",
            "2043" => "1004",
            "2044" => "1004",
            "2045" => "1004",
            "2046" => "1004",
            "2047" => "1004",
            "2048" => "1005",
            "2049" => "1005",
            "2050" => "1005",
            "2051" => "1005",
            "2052" => "1005",
            "2053" => "1005",
            "2054" => "1005",
            "2055" => "1005",
            "2056" => "1005",
            "2057" => "1006",
            "2058" => "1006",
            "2059" => "1006",
            "2060" => "1006",
            "2061" => "1006",
            "2062" => "1006",
            "2063" => "1006",
            "2064" => "1006",
            "2065" => "1006",
            "2066" => "1006",
            "2067" => "1006",
            "2068" => "1006",
            "2069" => "1006",
            "2070" => "1007",
            "2071" => "1007",
            "2072" => "1007",
            "2073" => "1007",
            "2074" => "1007",
            "2075" => "1007",
            "2076" => "1008",
            "2078" => "1008",
            "2079" => "1008",
            "2080" => "1008",
            "2081" => "1008",
            "2082" => "1008",
            "2083" => "1008",
            "2084" => "1008",
            "2085" => "1008",
            "2086" => "1009",
            "2087" => "1009",
            "2088" => "1009",
            "2089" => "1009",
            "2090" => "1009",
            "2091" => "1009",
            "2092" => "1009",
            "2093" => "1009",
            "2094" => "1009",
            "2096" => "1009",
            "2097" => "1009",
            "2098" => "1009",
            "2099" => "1009",
            "2100" => "1009",
            "2101" => "1010",
            "2102" => "1010",
            "2103" => "1010",
            "2104" => "1010",
            "2105" => "1010",
            "2106" => "1010",
            "2107" => "1010",
            "2108" => "1010",
            "2109" => "1010",
            "2110" => "1010",
            "2111" => "1010",
            "2112" => "1010",
            "2113" => "1010",
            "2114" => "1010",
            "2115" => "1010",
            "2116" => "1010",
            "2117" => "1010",
            "2118" => "1010",
            "2119" => "1010",
            "2120" => "1010",
            "2121" => "1010",
            "2122" => "1010",
            "2123" => "1010",
            "2124" => "1011",
            "2125" => "1011",
            "2126" => "1011",
            "2127" => "1011",
            "2128" => "1011",
            "2129" => "1011",
            "2130" => "1011",
            "2131" => "1011",
            "2132" => "1012",
            "2133" => "1012",
            "2134" => "1012",
            "2135" => "1012",
            "2136" => "1012",
            "2137" => "1013",
            "2138" => "1014",
            "2139" => "1014",
            "2140" => "1014",
            "2141" => "1014",
            "2142" => "1014",
            "2143" => "1014",
            "2144" => "1014",
            "2145" => "1015",
            "2146" => "1015",
            "2147" => "1015",
            "2148" => "1015",
            "2149" => "1015",
            "2150" => "1015",
            "2151" => "1015",
            "2152" => "1015",
            "2153" => "1015",
            "2154" => "1015",
            "2155" => "1015",
            "2156" => "1015",
            "2157" => "1015",
            "2158" => "1015",
            "2159" => "1015",
            "2160" => "1015",
            "2161" => "1015",
            "2162" => "1015",
            "2163" => "1015",
            "2164" => "1016",
            "2165" => "1016",
            "2166" => "1016",
            "2167" => "1016",
            "2168" => "1016",
            "2169" => "1016",
            "2171" => "1017",
            "2172" => "1017",
            "2173" => "1017",
            "2174" => "1017",
            "2175" => "1017",
            "2176" => "1018",
            "2177" => "1018",
            "2178" => "1019",
            "2179" => "1019",
            "2180" => "1019",
            "2181" => "1019",
            "2182" => "1019",
            "2183" => "1019",
            "2184" => "1019",
            "2185" => "1020",
            "2186" => "1020",
            "2187" => "1020",
            "2188" => "1020",
            "2189" => "1020",
            "2190" => "1020",
            "2191" => "1020",
            "2192" => "1020",
            "2193" => "1020",
            "2194" => "1020",
            "2195" => "1020",
            "2196" => "1020",
            "2197" => "1020",
            "2198" => "1020",
            "2199" => "1020",
            "2200" => "1020",
            "2201" => "1020",
            "2202" => "1020",
            "2203" => "1020",
            "2204" => "1020",
            "2205" => "1020",
            "2206" => "1020",
            "2207" => "1020",
            "2208" => "1020",
            "2209" => "1020",
            "2210" => "1020",
            "2211" => "1020",
            "2212" => "1020",
            "2213" => "1020",
            "2214" => "1020",
            "2215" => "1020",
            "2216" => "1020",
            "2217" => "1020",
            "2218" => "1020",
            "2219" => "1020",
            "2220" => "1020",
            "2221" => "1020",
            "2222" => "1020",
            "2223" => "1020",
            "2224" => "1020",
            "2225" => "1020",
            "2226" => "1020",
            "2227" => "1020",
            "2228" => "1020",
            "2229" => "1020",
            "2230" => "1020",
            "2231" => "1020",
            "2232" => "1020",
            "2233" => "1020",
            "2234" => "1020",
            "2235" => "1020",
            "2236" => "1020",
            "2237" => "1021",
            "2238" => "1021",
            "2239" => "1021",
            "2240" => "1021",
            "2241" => "1021",
            "2242" => "1021",
            "2243" => "1021",
            "2244" => "1021",
            "2245" => "1021",
            "2246" => "1021",
            "2247" => "1021",
            "2248" => "1021",
            "2249" => "1021",
            "2250" => "1021",
            "2251" => "1021",
            "2252" => "1022",
            "2253" => "1022",
            "2254" => "1022",
            "2255" => "1022",
            "2256" => "1022",
            "2257" => "1022",
            "2258" => "1022",
            "2259" => "1022",
            "2260" => "1022",
            "2261" => "1023",
            "2262" => "1023",
            "2263" => "1023",
            "2264" => "1023",
            "2265" => "1023",
            "2266" => "1023",
            "2267" => "1023",
            "2268" => "1023",
            "2269" => "1023",
            "2270" => "1023",
            "2271" => "1023",
            "2272" => "1023",
            "2273" => "1023",
            "2274" => "1023",
            "2275" => "1024",
            "2276" => "1024",
            "2277" => "1024",
            "2278" => "1024",
            "2279" => "1024",
            "2280" => "1024",
            "2281" => "1024",
            "2282" => "1024",
            "2283" => "1024",
            "2284" => "1025",
            "2285" => "1025",
            "2286" => "1025",
            "2287" => "1025",
            "2288" => "1025",
            "2289" => "1025",
            "2290" => "1025",
            "2291" => "1025",
            "2292" => "1025",
            "2293" => "1025",
            "2294" => "1025",
            "2295" => "1025",
            "2296" => "1025",
            "2297" => "1025"
        );
        
        $this->_sub_category = array(
            "2000" => "Antique",
            "2001" => "Art and craft supplies",
            "2002" => "Art dealers and galleries",
            "2003" => "Camera and photographic supplies",
            "2004" => "Digital art",
            "2005" => "Memorabilia",
            "2006" => "Music store (instruments and sheet music)",
            "2007" => "Sewing, needlework, and fabrics",
            "2008" => "Stamp and coin",
            "2009" => "Stationary, printing and writing paper",
            "2010" => "Vintage and collectibles",
            "2011" => "Clothing",
            "2012" => "Furniture",
            "2013" => "Baby products (other)",
            "2014" => "Safety and health",
            "2015" => "Bath and body",
            "2016" => "Fragrances and perfumes",
            "2017" => "Makeup and cosmetics",
            "2018" => "Audio books",
            "2019" => "Digital content",
            "2020" => "Educational and textbooks",
            "2021" => "Fiction and nonfiction",
            "2022" => "Magazines",
            "2023" => "Publishing and printing",
            "2024" => "Rare and used books",
            "2025" => "Accounting",
            "2026" => "Advertising",
            "2027" => "Agricultural",
            "2028" => "Architectural, engineering, and surveying services",
            "2029" => "Chemicals and allied products",
            "2030" => "Commercial photography, art, and graphics",
            "2031" => "Construction",
            "2032" => "Consulting services",
            "2033" => "Educational services",
            "2034" => "Equipment rentals and leasing services",
            "2035" => "Equipment repair services",
            "2036" => "Hiring services",
            "2037" => "Industrial and manufacturing supplies",
            "2038" => "Mailing lists",
            "2039" => "Marketing",
            "2040" => "Multi-level marketing",
            "2041" => "Office and commercial furniture",
            "2042" => "Office supplies and equipment",
            "2043" => "Publishing and printing",
            "2044" => "Quick copy and reproduction services",
            "2045" => "Shipping and packing",
            "2046" => "Stenographic and secretarial support services",
            "2047" => "Wholesale",
            "2048" => "Children’s clothing",
            "2049" => "Men’s clothing",
            "2050" => "Women’s clothing",
            "2051" => "Shoes",
            "2052" => "Military and civil service uniforms",
            "2053" => "Accessories",
            "2054" => "Retail (fine jewelry and watches)",
            "2055" => "Wholesale (precious stones and metals)",
            "2056" => "Fashion jewelry",
            "2057" => "Computer and data processing services",
            "2058" => "Desktops, laptops, and notebooks",
            "2059" => "Digital content",
            "2060" => "eCommerce services",
            "2061" => "Maintenance and repair services",
            "2062" => "Monitors and projectors",
            "2063" => "Networking",
            "2064" => "Online gaming",
            "2065" => "Parts and accessories",
            "2066" => "Peripherals",
            "2067" => "Software",
            "2068" => "Training services",
            "2069" => "Web hosting and design",
            "2070" => "Business and secretarial schools",
            "2071" => "Child daycare services",
            "2072" => "Colleges and universities",
            "2073" => "Dance halls, studios, and schools",
            "2074" => "Elementary and secondary schools",
            "2075" => "Vocational and trade schools",
            "2076" => "Cameras, camcorders, and equipment",
            "2078" => "Cell phones, PDAs, and pagers",
            "2079" => "General electronic accessories",
            "2080" => "Home audio",
            "2081" => "Home electronics",
            "2082" => "Security and surveillance",
            "2083" => "Telecommunication equipment and sales",
            "2084" => "Telecommunication services",
            "2085" => "Telephone cards",
            "2086" => "Memorabilia",
            "2087" => "Movie tickets",
            "2088" => "Movies (DVDs, videotapes)",
            "2089" => "Music (CDs, cassettes and albums)",
            "2090" => "Cable, satellite, and other pay TV and radio",
            "2091" => "Adult digital content",
            "2092" => "Concert tickets",
            "2093" => "Theater tickets",
            "2094" => "Toys and games",
            "2096" => "Digital content",
            "2097" => "Entertainers",
            "2098" => "Gambling",
            "2099" => "Online games",
            "2100" => "Video games and systems",
            "2101" => "Accounting",
            "2102" => "Collection agency",
            "2103" => "Commodities and futures exchange",
            "2104" => "Consumer credit reporting agencies",
            "2105" => "Debt counseling services",
            "2106" => "Credit union",
            "2107" => "Currency dealer and currency exchange",
            "2108" => "Escrow",
            "2109" => "Finance company",
            "2110" => "Financial and investment advice",
            "2111" => "Insurance (auto and home)",
            "2112" => "Insurance (life and annuity)",
            "2113" => "Investments (general)",
            "2114" => "Money service business",
            "2115" => "Mortgage brokers or dealers",
            "2116" => "Online gaming currency",
            "2117" => "Paycheck lender or cash advance",
            "2118" => "Prepaid and stored value cards",
            "2119" => "Real estate agent",
            "2120" => "Remittance",
            "2121" => "Rental property management",
            "2122" => "Security brokers and dealers",
            "2123" => "Wire transfer and money order",
            "2124" => "Alcoholic beverages",
            "2125" => "Catering services",
            "2126" => "Coffee and tea",
            "2127" => "Gourmet foods",
            "2128" => "Specialty and miscellaneous food stores",
            "2129" => "Restaurant",
            "2130" => "Tobacco",
            "2131" => "Vitamins and supplements",
            "2132" => "Florist",
            "2133" => "Gift, card, novelty, and souvenir shops",
            "2134" => "Gourmet foods",
            "2135" => "Nursery plants and flowers",
            "2136" => "Party supplies",
            "2137" => "Government services (not elsewhere classified)",
            "2138" => "Drugstore (excluding prescription drugs)",
            "2139" => "Drugstore (including prescription drugs)",
            "2140" => "Dental care",
            "2141" => "Medical care",
            "2142" => "Medical equipment and supplies",
            "2143" => "Vision care",
            "2144" => "Vitamins and supplements",
            "2145" => "Antiques",
            "2146" => "Appliances",
            "2147" => "Art dealers and galleries",
            "2148" => "Bed and bath",
            "2149" => "Construction material",
            "2150" => "Drapery, window covering, and upholstery",
            "2151" => "Exterminating and disinfecting services",
            "2152" => "Fireplace, and fireplace screens",
            "2153" => "Furniture",
            "2154" => "Garden supplies",
            "2155" => "Glass, paint, and wallpaper",
            "2156" => "Hardware and tools",
            "2157" => "Home décor",
            "2158" => "Housewares",
            "2159" => "Kitchenware",
            "2160" => "Landscaping",
            "2161" => "Rugs and carpets",
            "2162" => "Security and surveillance equipment",
            "2163" => "Swimming pools and spas",
            "2164" => "Charity",
            "2165" => "Political",
            "2166" => "Religious",
            "2167" => "Other",
            "2168" => "Personal",
            "2169" => "Educational",
            "2171" => "Medication and supplements",
            "2172" => "Pet shops, pet food, and supplies",
            "2173" => "Specialty or rare pets",
            "2174" => "Veterinary services",
            "2175" => "Membership services",
            "2176" => "Merchandise",
            "2177" => "Services (not elsewhere classified)",
            "2178" => "Chemicals and allied products",
            "2179" => "Department store",
            "2180" => "Discount store",
            "2181" => "Durable goods",
            "2182" => "Non-durable goods",
            "2183" => "Used and secondhand store",
            "2184" => "Variety store",
            "2185" => "Advertising",
            "2186" => "Shopping services and buying clubs",
            "2187" => "Career services",
            "2188" => "Carpentry",
            "2189" => "Child care services",
            "2190" => "Cleaning and maintenance",
            "2191" => "Commercial photography",
            "2192" => "Computer and data processing services",
            "2193" => "Computer network services",
            "2194" => "Consulting services",
            "2195" => "Counseling services",
            "2196" => "Courier services",
            "2197" => "Dental care",
            "2198" => "eCommerce services",
            "2199" => "Electrical and small appliance repair",
            "2200" => "Entertainment",
            "2201" => "Equipment rental and leasing services",
            "2202" => "Event and wedding planning",
            "2203" => "Gambling",
            "2204" => "General contractors",
            "2205" => "Graphic and commercial design",
            "2206" => "Health and beauty spas",
            "2207" => "IDs, licenses, and passports",
            "2208" => "Importing and exporting",
            "2209" => "Information retrieval services",
            "2210" => "Insurance – auto and home",
            "2211" => "Insurance – life and annuity",
            "2212" => "Landscaping and horticultural",
            "2213" => "Legal services and attorneys",
            "2214" => "Local delivery service",
            "2215" => "Lottery and contests",
            "2216" => "Medical care",
            "2217" => "Membership clubs and organizations",
            "2218" => "Misc. publishing and printing",
            "2219" => "Moving and storage",
            "2220" => "Online dating",
            "2221" => "Photofinishing",
            "2222" => "Photographic studios – portraits",
            "2223" => "Protective and security services",
            "2224" => "Quick copy and reproduction services",
            "2225" => "Radio, television, and stereo repair",
            "2226" => "Real estate agent",
            "2227" => "Rental property management",
            "2228" => "Reupholstery and furniture repair",
            "2229" => "Services (not elsewhere classified)",
            "2230" => "Shipping and packing",
            "2231" => "Swimming pool services",
            "2232" => "Tailors and alterations",
            "2233" => "Telecommunication service",
            "2234" => "Utilities",
            "2235" => "Vision care",
            "2236" => "Watch, clock, and jewelry repair",
            "2237" => "Athletic shoes",
            "2238" => "Bicycle shop, service, and repair",
            "2239" => "Boating, sailing and accessories",
            "2240" => "Camping and outdoors",
            "2241" => "Dance halls, studios, and schools",
            "2242" => "Exercise and fitness",
            "2243" => "Fan gear and memorabilia",
            "2244" => "Firearm accessories",
            "2245" => "Firearms",
            "2246" => "Hunting",
            "2247" => "Knives",
            "2248" => "Martial arts weapons",
            "2249" => "Sport games and toys",
            "2250" => "Sporting equipment",
            "2251" => "Swimming pools and spas",
            "2252" => "Arts and crafts",
            "2253" => "Camera and photographic supplies",
            "2254" => "Hobby, toy, and game shops",
            "2255" => "Memorabilia",
            "2256" => "Music store – instruments and sheet music",
            "2257" => "Stamp and coin",
            "2258" => "Stationary, printing, and writing paper",
            "2259" => "Vintage and collectibles",
            "2260" => "Video games and systems",
            "2261" => "Airline",
            "2262" => "Auto rental",
            "2263" => "Bus line",
            "2264" => "Cruises",
            "2265" => "Lodging and accommodations",
            "2266" => "Luggage and leather goods",
            "2267" => "Recreational services",
            "2268" => "Sporting and recreation camps",
            "2269" => "Taxicabs and limousines",
            "2270" => "Timeshares",
            "2271" => "Tours",
            "2272" => "Trailer parks or campgrounds",
            "2273" => "Transportation services – other",
            "2274" => "Travel agency",
            "2275" => "Auto dealer – new and used",
            "2276" => "Auto dealer – used only",
            "2277" => "Aviation",
            "2278" => "Boat dealer",
            "2279" => "Mobile home dealer",
            "2280" => "Motorcycle dealer",
            "2281" => "Recreational and utility trailer dealer",
            "2282" => "Recreational vehicle dealer",
            "2283" => "Vintage and collectibles",
            "2284" => "New parts and supplies – motor vehicle",
            "2285" => "Used parts – motor vehicle",
            "2286" => "Audio and video",
            "2287" => "Auto body repair and paint",
            "2288" => "Auto rental",
            "2289" => "Auto service",
            "2290" => "Automotive tire supply and service",
            "2291" => "Boat rental and leases",
            "2292" => "Car wash",
            "2293" => "Motor home and recreational vehicle rental",
            "2294" => "Tools and equipment",
            "2295" => "Towing service",
            "2296" => "Truck and utility trailer rental",
            "2297" => "Accessories"
        );
        
        $this->_supported_language = array(
            "da_DK" => "Danish",
            "de_DE" => "German",
            "en_AU" => "English (Australian)",
            "en_GB" => "English (British)",
            "en_US" => "English (American)",
            "es_ES" => "Spanish (Spain)",
            "es_XC" => "Spanish (International)",
            "fr_CA" => "French (Canadian)",
            "fr_FR" => "French (France)",
            "fr_XC" => "French (International)",
            "he_IL" => "Hebrew",
            "id_ID" => "Indonesian",
            "it_IT" => "Italian",
            "ja_JP" => "Japanese",
            "nl_NL" => "Dutch",
            "no_NO" => "Norwegian",
            "pl_PL" => "Polish",
            "pt_BR" => "Portuguese (Brazil)",
            "pt_PT" => "Portuguese (Portugal)",
            "ru_RU" => "Russian",
            "sv_SE" => "Swedish",
            "th_TH" => "Thai",
            "tr_TR" => "Turkish",
            "zh_CN" => "Chinese (China)",
            "zh_HK" => "Chinese (Hong Kong)",
            "zh_TW" => "Chinese (Taiwan)",
            "zh_XC" => "Chinese (International)"
        );
    }
    
    public function get_auth_token(){
            /*          
             * Authentication
             * Before using any of the PayPal REST APIs, you must first authenticate yourself and
             * obtain an access token. This call uses the client token and secret assigned to you by
             * PayPal; all other calls will use the access token obtained here.
             */
            $Authheaders = array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $this->_auth_string,
            );
            $Request = "grant_type=client_credentials";
            $AuthURL = $this->EndPointURL.'oauth2/token';
            $AuthResponseJson = $this->CURLRequest('POST',$Authheaders,$AuthURL,$Request);
            $AuthResponseArray =json_decode($AuthResponseJson, true);
            /* If token is is received then we can process for ISP */
            if(isset($AuthResponseArray['token_type']) && isset($AuthResponseArray['access_token'])){
                return $AuthResponseArray['access_token'];
            }
            else{                
                $returnArray['headers'] = $Authheaders;
                $returnArray['EndPointURL'] = $AuthURL;
                $returnArray['RAWREQUEST'] = $Request;
                $returnArray['RAWRESPONSE'] = $AuthResponseArray;                
                return $AuthResponseArray;
            }
            //$this->Logger($this->LogPath, __FUNCTION__.'Request', $Request);
            //$this->Logger($this->LogPath, __FUNCTION__.'Response',$Response);                        
    }

    public function IntegratedSignup($requestData) {
            
            $AuthToken = $this->get_auth_token();
            if(is_array($AuthToken)){
                return $AuthToken;
            }
            else{
                $this->_bearer_string =  $AuthToken;
            }
            $TrimmedArray = $this->array_trim($requestData);
            $RequestPayload = json_encode($TrimmedArray, 0 | 64);
            $CPheaders = array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->_bearer_string,
            );
            $CPURL = $this->EndPointURL.'customer/partner-referrals';
            $ConnectPayPalJson = $this->CURLRequest('POST',$CPheaders,$CPURL,$RequestPayload);
            $ConnectPayPalArray = json_decode($ConnectPayPalJson, true);
            
            $returnArray['headers'] = $CPheaders;
            $returnArray['EndPointURL'] = $CPURL;
            $returnArray['RAWREQUEST'] = $TrimmedArray;
            $returnArray['RAWRESPONSE'] = $ConnectPayPalArray;
            
            return $returnArray;            
    }
    
    public function getPayerID($PartnerID,$MerchantTrackingID){
        $PIDheaders = array(            
            'Authorization: Bearer ' . $this->_bearer_string,
            'Accept : */*'
        );
        $PIDURL = $this->EndPointURL.'/customer/partners/'.$PartnerID.'/merchant-integrations?tracking_id='.$MerchantTrackingID;
        $ResponseJson = $this->CURLRequest('GET',$PIDheaders,$PIDURL);
        $ResponseArray = json_decode($ResponseJson, true);
        return $ResponseArray;
    }
    
    public function getAccountDetails ($PartnerID,$MerchantID){
        $AuthToken = $this->get_auth_token();
        $this->_bearer_string =  $AuthToken;
        
        $ADheaders = array(
            'Authorization: Bearer ' . $this->_bearer_string,
            'Accept : */*'
        );
        $ADURL = $this->EndPointURL.'customer/partners/'.$PartnerID.'/merchant-integrations/'.$MerchantID;
        
        $ResponseJson = $this->CURLRequest('GET',$ADheaders,$ADURL);
        $ResponseArray = json_decode($ResponseJson, true);
        
        $returnArray['headers'] = $ADheaders;
        $returnArray['EndPointURL'] = $ADURL;
        $returnArray['RAWREQUEST'] = '';
        $returnArray['RAWRESPONSE'] = $ResponseArray;
        return $returnArray;
    }
    
    function CURLRequest($method,$headers,$url,$Request='') {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_VERBOSE, $this->Sandbox);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        if($method==='POST'){            
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $Request);
        }        
        curl_setopt($curl, CURLOPT_URL,$url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);        
        /*
        * If a cURL error occurs, output it for review.
        */
       if($this->Sandbox)
       {
               if(curl_error($curl))
               {
                       echo curl_error($curl).'<br /><br />';	
               }
       }

       curl_close($curl);
       return $result;
    }
    
    /**
     * Save log info to a location on the disk.
     *
     * @param $log_path
     * @param $filename
     * @param $string_data
     * @return bool
     */
    function Logger($log_path, $filename, $string_data)
	{

        if($this->LogResults)
        {
            $timestamp = strtotime('now');
            $timestamp = date('mdY_gi_s_A_',$timestamp);

            $string_data_array = $this->NVPToArray($string_data);

            $file = $log_path.$timestamp.$filename.'.txt';
            $fh = fopen($file, 'w');
            fwrite($fh, $string_data.chr(13).chr(13).print_r($string_data_array, true));
            fclose($fh);
        }
		
		return true;	
	}
    
    public function array_trim($input) {
        return is_array($input) ? array_filter($input, 
            function (& $value) { return $value = $this->array_trim($value); }
        ) : $input;
    }

}
