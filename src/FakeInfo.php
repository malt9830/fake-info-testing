<?php

/**
 * Class FakeInfo
 * @author  Arturo Mora-Rioja
 * @version 1.0.0 March 2023
 */

class FakeInfo {

    public const DATE_FORMAT = 'd/m/Y';
    public const GENDER_FEMININE = 'F';
    public const GENDER_MASCULINE = 'M';
    private const FILE_PERSON_NAMES = 'data/person-names.json';
    // private const PHONE_PREFIXES = [
    //     2, 30, 31, 40, 41, 42, 50, 51, 52, 53, 60, 61, 71, 81, 91, 92, 93, 342, 344, 345, 346, 347, 348, 349, 356, 357, 
    //     359, 362, 365, 366, 389, 398, 431, 441, 462, 466, 468, 472, 474, 476, 478, 485, 486, 488, 489, 493, 494, 495, 496, 
    //     498, 499, 542, 543, 545, 551, 552, 556, 571, 572, 573, 574, 577, 579, 584, 586, 587, 589, 597, 598, 627, 629, 
    //     641, 649, 658, 662, 663, 664, 665, 667, 692, 693, 694, 697, 771, 772, 782, 783, 785, 786, 788, 789, 826, 827, 829
    // ];
    private const PHONE_PREFIXES = [
        '2', '30', '31', '40', '41', '42', '50', '51', '52', '53', '60', '61', '71', '81', '91', '92', '93', '342', 
        '344', '345', '346', '347', '348', '349', '356', '357', '359', '362', '365', '366', '389', '398', '431', 
        '441', '462', '466', '468', '472', '474', '476', '478', '485', '486', '488', '489', '493', '494', '495', 
        '496', '498', '499', '542', '543', '545', '551', '552', '556', '571', '572', '573', '574', '577', '579', 
        '584', '586', '587', '589', '597', '598', '627', '629', '641', '649', '658', '662', '663', '664', '665', 
        '667', '692', '693', '694', '697', '771', '772', '782', '783', '785', '786', '788', '789', '826', '827', '829'
    ];

    private string $cpr;    
    private string $firstName;
    private string $lastName;
    private string $gender;
    private string $birthDate;
    private array $address = [];
    private string $phone;  

    public function __construct()
    {
        $this->setFullNameAndGender();
        $this->setBirthDate();
        $this->setCpr();
        $this->setPhone();
    }
    
    /**
     * Generates a fake CPR based on the existing birth date and gender.
     * - If no birth date exists, it generates it.
     * - If no first name, last name or gender exists, it generates them.
     */
    private function setCpr(): void
    {
        if (!isset($this->birthDate)) {
            $this->setBirthDate();        
        }
        if (!isset($this->firstName) || !isset($this->lastName) || (!isset($this->gender))){
            $this->setFullNameAndGender();
        }
        // The CPR must end in an even number for females, odd for males
        $finalDigit = mt_rand(0, 9);
        if ($this->gender === self::GENDER_FEMININE && fmod($finalDigit, 2) === 1) {
            $finalDigit++;
        }
        
        $this->cpr = substr($this->birthDate, 8, 2) . 
            substr($this->birthDate, 5, 2) .
            substr($this->birthDate, 2, 2) .
            self::getRandomDigit() .
            self::getRandomDigit() .
            self::getRandomDigit() .
            $finalDigit;
    }

    /**
     * Generates a fake date of birth from 1900 to the present year.
     */
    private function setBirthDate(): void 
    {
        $year = mt_rand(1900, date('Y'));
        $month = mt_rand(1, 12);
        if (in_array($month, [1, 3, 5, 7, 8, 10, 12])) {
            $day = mt_rand(1, 31);
        } else if (in_array($month, [4, 6, 9, 11])) {
            $day = mt_rand(1, 30);
        } else {
            // Leap years are not taken into account
            // so as not to overcomplicate the code
            $day = mt_rand(1, 28);
        }
        $this->birthDate = date_format(date_create("$year-$month-$day"), 'Y-m-d');
    }

    /**
     * Generates a fake full name and gender.
     * - The generation consists in extracting them randomly from the person's JSON file.
     */
    private function setFullNameAndGender(): void
    {
        $names = json_decode(file_get_contents(self::FILE_PERSON_NAMES), true);
        $person = $names['persons'][mt_rand(0, count($names['persons']) - 1)];
        
        $this->firstName = $person['firstName'];
        $this->lastName = $person['lastName'];
        $this->gender = ($person['gender'] === 'female' ? self::GENDER_FEMININE : self::GENDER_MASCULINE);
    }

    /**
     * Generates a fake Danish phone number
     * - The phone number must start with a prefix in self::PHONE_PREFIXES
     */
    private function setPhone(): void
    {
        $phone = self::PHONE_PREFIXES[mt_rand(0, count(self::PHONE_PREFIXES) - 1)];
        $prefixLength = strlen($phone);
        for ($index = 0; $index < (8 - $prefixLength); $index++) {
            $phone .= self::getRandomDigit();
        }

        $this->phone = $phone;
    }

    /**
     * Returns a fake CPR.
     * - If it does not exist already, it generates a new one.
     * - Since the CPR depends on the date of birth and the gender,
     *   if any of these do not exist, they are also generated
     * 
     * @return string The fake CPR
     */
    public function getCpr(): string {
        return $this->cpr; 
    }
    
    /**
     * Returns a fake full name and gender
     * 
     * @return array ['firstName' => value, 'lastName' => value, 'gender' => 'female' | 'male']
     */
    public function getFullNameAndGender(): array 
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'gender' => $this->gender
        ];
    }

    /**
     * Returns a fake full name, gender, and birth date
     * 
     * @return array ['firstName' => value, 'lastName' => value, 'gender' => 'female' | 'male', 'birthDate' => value]
     */
    public function getFullNameGenderAndBirthDate(): array 
    {
        return [
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'gender' => $this->gender,
            'birthDate' => $this->birthDate
        ];
    }

    /**
     * Returns a fake Danish phone number
     * 
     * @return string The fake phone number
     */
    public function getPhoneNumber(): string
    {
        return $this->phone;
    }

    /** 
     * Returns information about several fake persons
     * 
     * @param $amount The number of fake persons to generate, between 2 and 200 inclusive
     * @return array The fake information
     */
    public function getFakePersons(int $amount = 2): array {
        if ($amount < 2) { $amount = 2; }
        if ($amount > 200) { $amount = 200; }

        for ($index = 0; $index < $amount; $index++) {
            $fakeInfo = new FakeInfo;
            $bulkInfo[] = [
                'CPR' => $fakeInfo->cpr,
                'firstName' => $fakeInfo->firstName,
                'lastName' => $fakeInfo->lastName,
                'gender' => ($fakeInfo->gender === self::GENDER_FEMININE ? 'female' : 'male'),
                'birthDate' => $fakeInfo->birthDate,
                'phoneNumber' => $fakeInfo->phone
            ];
        }
        return $bulkInfo;
    }

    private static function getRandomDigit(): string 
    {
        return (string) mt_rand(0, 9);
    }
}