<?php

namespace App\Support;

/**
 * Static reference data for the Egyptian used-car market.
 * Plain PHP (no framework calls) so the SQL dump builder can reuse it.
 */
class EgyptData
{
    /** English colour name => [hex, Arabic name] */
    public const COLORS = [
        'White' => ['#f3f3ef', 'أبيض'],
        'Black' => ['#17191d', 'أسود'],
        'Silver' => ['#b9bec7', 'فضي'],
        'Grey' => ['#6b7280', 'رمادي'],
        'Red' => ['#c0262d', 'أحمر'],
        'Blue' => ['#1f55b3', 'أزرق'],
        'Navy' => ['#14284b', 'كحلي'],
        'Green' => ['#2f6b4a', 'أخضر'],
        'Beige' => ['#cdb99a', 'بيج'],
        'Gold' => ['#b8934a', 'ذهبي'],
        'Brown' => ['#5b3a29', 'بني'],
        'Orange' => ['#d9641e', 'برتقالي'],
        'Burgundy' => ['#6d1a2b', 'عنابي'],
    ];

    /** Centre of each governorate (its capital city): [latitude, longitude] */
    public const COORDS = [
        'Cairo' => [30.0444, 31.2357], 'Giza' => [30.0131, 31.2089], 'Alexandria' => [31.2001, 29.9187],
        'Qalyubia' => [30.4660, 31.1850], 'Sharqia' => [30.5877, 31.5020], 'Dakahlia' => [31.0409, 31.3785],
        'Gharbia' => [30.7865, 31.0004], 'Monufia' => [30.5972, 30.9876], 'Beheira' => [31.0341, 30.4682],
        'Ismailia' => [30.5965, 32.2715], 'Port Said' => [31.2653, 32.3019], 'Suez' => [29.9668, 32.5498],
        'Damietta' => [31.4165, 31.8133], 'Kafr El Sheikh' => [31.1107, 30.9388], 'Fayoum' => [29.3084, 30.8428],
        'Beni Suef' => [29.0661, 31.0994], 'Minya' => [28.0871, 30.7618], 'Asyut' => [27.1809, 31.1837],
        'Sohag' => [26.5591, 31.6948], 'Qena' => [26.1551, 32.7160], 'Luxor' => [25.6872, 32.6396],
        'Aswan' => [24.0889, 32.8998], 'Red Sea' => [27.2579, 33.8116], 'South Sinai' => [28.2415, 33.6221],
        'North Sinai' => [31.1316, 33.7984], 'New Valley' => [25.4390, 30.5586], 'Matrouh' => [31.3543, 27.2373],
    ];

    /** Arabic letters used on Egyptian plates */
    public const PLATE_LETTERS = ['أ', 'ب', 'ج', 'د', 'ر', 'س', 'ص', 'ط', 'ع', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'هـ', 'و', 'ى'];

    public const FIRST_NAMES = [
        'Ahmed', 'Mohamed', 'Mahmoud', 'Mostafa', 'Omar', 'Ali', 'Hassan', 'Hossam', 'Khaled', 'Tarek',
        'Karim', 'Youssef', 'Ibrahim', 'Amr', 'Sherif', 'Walid', 'Ayman', 'Hany', 'Sameh', 'Adel',
        'Mona', 'Heba', 'Nour', 'Salma', 'Dina', 'Rania', 'Nadine', 'Yasmin', 'Fatma', 'Amira',
    ];

    public const LAST_NAMES = [
        'Hassan', 'Ibrahim', 'Mansour', 'Saad', 'Farouk', 'Abdelrahman', 'Nasser', 'ElSayed', 'Fahmy', 'Salem',
        'Gad', 'Zaki', 'Khalil', 'Shawky', 'Morsy', 'Badawy', 'Lotfy', 'Ramadan', 'Soliman', 'Aly',
    ];

    /**
     * Governorate => [arabic, cities[en => ar], traffic units[en => ar]]
     * Traffic unit = the licensing office (وحدة المرور) that issued the licence.
     */
    public static function governorates(): array
    {
        return [
            'Cairo' => ['القاهرة',
                ['Nasr City' => 'مدينة نصر', 'Heliopolis' => 'مصر الجديدة', 'Maadi' => 'المعادي', 'New Cairo' => 'القاهرة الجديدة', 'Shubra' => 'شبرا', 'Mokattam' => 'المقطم', 'Zamalek' => 'الزمالك'],
                ['Nasr City Traffic' => 'مرور مدينة نصر', 'Heliopolis Traffic' => 'مرور مصر الجديدة', 'Maadi Traffic' => 'مرور المعادي', 'New Cairo Traffic' => 'مرور القاهرة الجديدة', 'Shubra Traffic' => 'مرور شبرا', 'Abbasiya Traffic' => 'مرور العباسية']],
            'Giza' => ['الجيزة',
                ['Dokki' => 'الدقي', 'Mohandessin' => 'المهندسين', 'Haram' => 'الهرم', '6th of October' => 'السادس من أكتوبر', 'Sheikh Zayed' => 'الشيخ زايد', 'Faisal' => 'فيصل'],
                ['Giza Traffic' => 'مرور الجيزة', 'Haram Traffic' => 'مرور الهرم', '6th of October Traffic' => 'مرور 6 أكتوبر', 'Dokki Traffic' => 'مرور الدقي']],
            'Alexandria' => ['الإسكندرية',
                ['Smouha' => 'سموحة', 'Sidi Gaber' => 'سيدي جابر', 'Miami' => 'ميامي', 'Agami' => 'العجمي', 'Borg El Arab' => 'برج العرب'],
                ['Montaza Traffic' => 'مرور المنتزه', 'Sidi Gaber Traffic' => 'مرور سيدي جابر', 'Borg El Arab Traffic' => 'مرور برج العرب']],
            'Qalyubia' => ['القليوبية', ['Banha' => 'بنها', 'Shubra El Kheima' => 'شبرا الخيمة', 'Obour' => 'العبور'], ['Banha Traffic' => 'مرور بنها', 'Shubra El Kheima Traffic' => 'مرور شبرا الخيمة']],
            'Sharqia' => ['الشرقية', ['Zagazig' => 'الزقازيق', '10th of Ramadan' => 'العاشر من رمضان', 'Belbeis' => 'بلبيس'], ['Zagazig Traffic' => 'مرور الزقازيق', '10th of Ramadan Traffic' => 'مرور العاشر من رمضان']],
            'Dakahlia' => ['الدقهلية', ['Mansoura' => 'المنصورة', 'Talkha' => 'طلخا', 'Mit Ghamr' => 'ميت غمر'], ['Mansoura Traffic' => 'مرور المنصورة', 'Mit Ghamr Traffic' => 'مرور ميت غمر']],
            'Gharbia' => ['الغربية', ['Tanta' => 'طنطا', 'El Mahalla' => 'المحلة الكبرى', 'Kafr El Zayat' => 'كفر الزيات'], ['Tanta Traffic' => 'مرور طنطا', 'El Mahalla Traffic' => 'مرور المحلة']],
            'Monufia' => ['المنوفية', ['Shebin El Kom' => 'شبين الكوم', 'Sadat City' => 'مدينة السادات', 'Menouf' => 'منوف'], ['Shebin El Kom Traffic' => 'مرور شبين الكوم', 'Sadat City Traffic' => 'مرور السادات']],
            'Beheira' => ['البحيرة', ['Damanhour' => 'دمنهور', 'Kafr El Dawwar' => 'كفر الدوار'], ['Damanhour Traffic' => 'مرور دمنهور', 'Kafr El Dawwar Traffic' => 'مرور كفر الدوار']],
            'Ismailia' => ['الإسماعيلية', ['Ismailia City' => 'مدينة الإسماعيلية', 'Fayed' => 'فايد'], ['Ismailia Traffic' => 'مرور الإسماعيلية']],
            'Port Said' => ['بورسعيد', ['Port Said City' => 'مدينة بورسعيد', 'Port Fouad' => 'بورفؤاد'], ['Port Said Traffic' => 'مرور بورسعيد']],
            'Suez' => ['السويس', ['Suez City' => 'مدينة السويس', 'Ain Sokhna' => 'العين السخنة'], ['Suez Traffic' => 'مرور السويس']],
            'Damietta' => ['دمياط', ['Damietta City' => 'مدينة دمياط', 'Ras El Bar' => 'رأس البر'], ['Damietta Traffic' => 'مرور دمياط']],
            'Kafr El Sheikh' => ['كفر الشيخ', ['Kafr El Sheikh City' => 'مدينة كفر الشيخ', 'Desouk' => 'دسوق'], ['Kafr El Sheikh Traffic' => 'مرور كفر الشيخ']],
            'Fayoum' => ['الفيوم', ['Fayoum City' => 'مدينة الفيوم', 'Sennuris' => 'سنورس'], ['Fayoum Traffic' => 'مرور الفيوم']],
            'Beni Suef' => ['بني سويف', ['Beni Suef City' => 'مدينة بني سويف', 'Nasser' => 'ناصر'], ['Beni Suef Traffic' => 'مرور بني سويف']],
            'Minya' => ['المنيا', ['Minya City' => 'مدينة المنيا', 'Mallawi' => 'ملوي'], ['Minya Traffic' => 'مرور المنيا']],
            'Asyut' => ['أسيوط', ['Asyut City' => 'مدينة أسيوط', 'Abnoub' => 'أبنوب'], ['Asyut Traffic' => 'مرور أسيوط']],
            'Sohag' => ['سوهاج', ['Sohag City' => 'مدينة سوهاج', 'Akhmim' => 'أخميم'], ['Sohag Traffic' => 'مرور سوهاج']],
            'Qena' => ['قنا', ['Qena City' => 'مدينة قنا', 'Nag Hammadi' => 'نجع حمادي'], ['Qena Traffic' => 'مرور قنا']],
            'Luxor' => ['الأقصر', ['Luxor City' => 'مدينة الأقصر', 'Esna' => 'إسنا'], ['Luxor Traffic' => 'مرور الأقصر']],
            'Aswan' => ['أسوان', ['Aswan City' => 'مدينة أسوان', 'Kom Ombo' => 'كوم أمبو'], ['Aswan Traffic' => 'مرور أسوان']],
            'Red Sea' => ['البحر الأحمر', ['Hurghada' => 'الغردقة', 'Safaga' => 'سفاجا'], ['Hurghada Traffic' => 'مرور الغردقة']],
            'South Sinai' => ['جنوب سيناء', ['Sharm El Sheikh' => 'شرم الشيخ', 'Dahab' => 'دهب'], ['Sharm El Sheikh Traffic' => 'مرور شرم الشيخ']],
            'North Sinai' => ['شمال سيناء', ['Arish' => 'العريش', 'Sheikh Zuweid' => 'الشيخ زويد'], ['Arish Traffic' => 'مرور العريش']],
            'New Valley' => ['الوادي الجديد', ['Kharga' => 'الخارجة', 'Dakhla' => 'الداخلة'], ['Kharga Traffic' => 'مرور الخارجة']],
            'Matrouh' => ['مطروح', ['Marsa Matrouh' => 'مرسى مطروح', 'El Alamein' => 'العلمين'], ['Matrouh Traffic' => 'مرور مطروح']],
        ];
    }

    /**
     * [brand, model, body, engine cc, new price (EGP thousands), first year, fuel, origin]
     * Origin: Local (assembled in Egypt) or Imported (customs cleared).
     */
    public static function models(): array
    {
        return [
            ['Toyota', 'Corolla', 'Sedan', 1600, 1450, 2008, 'Petrol', 'Local'],
            ['Toyota', 'Yaris', 'Hatchback', 1500, 1100, 2009, 'Petrol', 'Local'],
            ['Toyota', 'Camry', 'Sedan', 2500, 2300, 2010, 'Petrol', 'Imported'],
            ['Toyota', 'RAV4', 'SUV', 2000, 2200, 2012, 'Petrol', 'Imported'],
            ['Toyota', 'Fortuner', 'SUV', 2700, 2600, 2014, 'Diesel', 'Imported'],
            ['Toyota', 'Hilux', 'Pickup', 2700, 1900, 2012, 'Diesel', 'Imported'],
            ['Toyota', 'Hiace', 'Van', 2700, 2400, 2012, 'Petrol', 'Imported'],
            ['Hyundai', 'Elantra', 'Sedan', 1600, 1300, 2008, 'Petrol', 'Local'],
            ['Hyundai', 'Sonata', 'Sedan', 2000, 1500, 2011, 'Petrol', 'Local'],
            ['Hyundai', 'Accent RB', 'Sedan', 1600, 900, 2012, 'Petrol', 'Local'],
            ['Hyundai', 'Tucson', 'SUV', 1600, 1900, 2012, 'Petrol', 'Local'],
            ['Hyundai', 'Creta', 'SUV', 1500, 1200, 2017, 'Petrol', 'Local'],
            ['Hyundai', 'i10', 'Hatchback', 1200, 650, 2011, 'Petrol', 'Local'],
            ['Hyundai', 'H1', 'Van', 2500, 2100, 2012, 'Diesel', 'Imported'],
            ['Kia', 'Cerato', 'Sedan', 1600, 1200, 2009, 'Petrol', 'Local'],
            ['Kia', 'Sportage', 'SUV', 1600, 1900, 2012, 'Petrol', 'Local'],
            ['Kia', 'Picanto', 'Hatchback', 1200, 600, 2011, 'Petrol', 'Local'],
            ['Kia', 'Rio', 'Sedan', 1400, 900, 2012, 'Petrol', 'Local'],
            ['Kia', 'Sorento', 'SUV', 2400, 2300, 2014, 'Petrol', 'Imported'],
            ['Kia', 'Seltos', 'SUV', 1500, 1250, 2020, 'Petrol', 'Local'],
            ['Nissan', 'Sunny', 'Sedan', 1500, 800, 2008, 'Petrol', 'Local'],
            ['Nissan', 'Sentra', 'Sedan', 1600, 1200, 2013, 'Petrol', 'Local'],
            ['Nissan', 'Qashqai', 'SUV', 1600, 1700, 2014, 'Petrol', 'Imported'],
            ['Nissan', 'Juke', 'SUV', 1600, 1200, 2012, 'Petrol', 'Imported'],
            ['Nissan', 'Kicks', 'SUV', 1600, 1100, 2019, 'Petrol', 'Local'],
            ['Chevrolet', 'Optra', 'Sedan', 1600, 700, 2008, 'Petrol', 'Local'],
            ['Chevrolet', 'Cruze', 'Sedan', 1800, 1000, 2010, 'Petrol', 'Local'],
            ['Chevrolet', 'Aveo', 'Sedan', 1500, 700, 2010, 'Petrol', 'Local'],
            ['Chevrolet', 'Captiva', 'SUV', 2400, 1400, 2011, 'Petrol', 'Local'],
            ['Daewoo', 'Lanos', 'Sedan', 1500, 300, 2008, 'Petrol', 'Local'],
            ['Mitsubishi', 'Lancer', 'Sedan', 1600, 800, 2008, 'Petrol', 'Local'],
            ['Mitsubishi', 'Attrage', 'Sedan', 1200, 750, 2015, 'Petrol', 'Imported'],
            ['Mitsubishi', 'Outlander', 'SUV', 2400, 1800, 2013, 'Petrol', 'Imported'],
            ['Mitsubishi', 'Eclipse Cross', 'SUV', 1500, 1700, 2019, 'Petrol', 'Imported'],
            ['Mitsubishi', 'L200', 'Pickup', 2400, 1700, 2014, 'Diesel', 'Imported'],
            ['Skoda', 'Octavia A7', 'Sedan', 1400, 1300, 2014, 'Petrol', 'Local'],
            ['Skoda', 'Fabia', 'Hatchback', 1200, 800, 2010, 'Petrol', 'Local'],
            ['Skoda', 'Kodiaq', 'SUV', 1400, 2100, 2018, 'Petrol', 'Imported'],
            ['Skoda', 'Superb', 'Sedan', 1800, 1800, 2012, 'Petrol', 'Imported'],
            ['Renault', 'Logan', 'Sedan', 1600, 650, 2009, 'Petrol', 'Local'],
            ['Renault', 'Duster', 'SUV', 1600, 1100, 2013, 'Petrol', 'Local'],
            ['Renault', 'Megane', 'Sedan', 1600, 900, 2010, 'Petrol', 'Local'],
            ['Renault', 'Sandero', 'Hatchback', 1600, 700, 2012, 'Petrol', 'Local'],
            ['Renault', 'Captur', 'SUV', 1600, 1000, 2017, 'Petrol', 'Local'],
            ['Fiat', 'Tipo', 'Sedan', 1600, 900, 2017, 'Petrol', 'Local'],
            ['Fiat', 'Punto', 'Hatchback', 1400, 600, 2010, 'Petrol', 'Local'],
            ['Fiat', '128', 'Sedan', 1300, 250, 2008, 'Petrol', 'Local'],
            ['Suzuki', 'Swift', 'Hatchback', 1200, 800, 2012, 'Petrol', 'Local'],
            ['Suzuki', 'Ertiga', 'Van', 1500, 900, 2016, 'Petrol', 'Local'],
            ['Suzuki', 'Dzire', 'Sedan', 1200, 700, 2018, 'Petrol', 'Local'],
            ['Suzuki', 'Vitara', 'SUV', 1600, 1200, 2016, 'Petrol', 'Imported'],
            ['Peugeot', '301', 'Sedan', 1600, 800, 2013, 'Petrol', 'Local'],
            ['Peugeot', '508', 'Sedan', 1600, 1700, 2012, 'Petrol', 'Imported'],
            ['Peugeot', '3008', 'SUV', 1600, 1900, 2017, 'Petrol', 'Imported'],
            ['Peugeot', '208', 'Hatchback', 1200, 900, 2015, 'Petrol', 'Imported'],
            ['Citroen', 'C-Elysee', 'Sedan', 1600, 700, 2013, 'Petrol', 'Local'],
            ['BMW', '320i', 'Sedan', 2000, 2600, 2010, 'Petrol', 'Imported'],
            ['BMW', '520i', 'Sedan', 2000, 3300, 2011, 'Petrol', 'Imported'],
            ['BMW', 'X1', 'SUV', 1500, 2500, 2013, 'Petrol', 'Imported'],
            ['BMW', 'X5', 'SUV', 3000, 5500, 2012, 'Petrol', 'Imported'],
            ['Mercedes-Benz', 'C180', 'Sedan', 1600, 2900, 2010, 'Petrol', 'Imported'],
            ['Mercedes-Benz', 'CLA 200', 'Sedan', 1600, 3300, 2014, 'Petrol', 'Imported'],
            ['Mercedes-Benz', 'E200', 'Sedan', 2000, 3900, 2011, 'Petrol', 'Imported'],
            ['Mercedes-Benz', 'GLC 200', 'SUV', 2000, 4500, 2016, 'Petrol', 'Imported'],
            ['Mercedes-Benz', 'A200', 'Hatchback', 1300, 2400, 2014, 'Petrol', 'Imported'],
            ['MG', 'MG5', 'Sedan', 1500, 900, 2021, 'Petrol', 'Local'],
            ['MG', 'ZS', 'SUV', 1500, 1100, 2019, 'Petrol', 'Local'],
            ['MG', 'MG6', 'Sedan', 1500, 1200, 2020, 'Petrol', 'Local'],
            ['MG', 'HS', 'SUV', 1500, 1500, 2020, 'Petrol', 'Local'],
            ['Chery', 'Tiggo 7 Pro', 'SUV', 1500, 1100, 2021, 'Petrol', 'Local'],
            ['Chery', 'Arrizo 5', 'Sedan', 1500, 800, 2019, 'Petrol', 'Local'],
            ['Chery', 'Tiggo 8', 'SUV', 1600, 1500, 2021, 'Petrol', 'Local'],
            ['Opel', 'Astra', 'Hatchback', 1600, 900, 2008, 'Petrol', 'Local'],
            ['Opel', 'Corsa', 'Hatchback', 1400, 600, 2010, 'Petrol', 'Local'],
            ['Honda', 'Civic', 'Sedan', 1500, 1500, 2008, 'Petrol', 'Imported'],
            ['Honda', 'City', 'Sedan', 1500, 1000, 2012, 'Petrol', 'Imported'],
            ['Honda', 'CR-V', 'SUV', 1500, 2100, 2013, 'Petrol', 'Imported'],
            ['Ford', 'Focus', 'Hatchback', 1600, 800, 2008, 'Petrol', 'Imported'],
            ['Ford', 'Fiesta', 'Hatchback', 1400, 650, 2010, 'Petrol', 'Imported'],
            ['Ford', 'EcoSport', 'SUV', 1500, 900, 2015, 'Petrol', 'Imported'],
            ['Jeep', 'Compass', 'SUV', 1400, 2000, 2017, 'Petrol', 'Imported'],
            ['Jeep', 'Cherokee', 'SUV', 2400, 2200, 2014, 'Petrol', 'Imported'],
            ['Volkswagen', 'Golf', 'Hatchback', 1400, 1400, 2010, 'Petrol', 'Imported'],
            ['Volkswagen', 'Jetta', 'Sedan', 1600, 1200, 2010, 'Petrol', 'Imported'],
            ['Volkswagen', 'Tiguan', 'SUV', 1400, 2000, 2013, 'Petrol', 'Imported'],
            ['BYD', 'F3', 'Sedan', 1500, 500, 2010, 'Petrol', 'Local'],
            ['BYD', 'Song Plus', 'SUV', 1500, 1800, 2022, 'Hybrid', 'Imported'],
            ['BYD', 'Atto 3', 'SUV', 0, 2000, 2023, 'Electric', 'Imported'],
            ['Geely', 'Emgrand', 'Sedan', 1500, 800, 2012, 'Petrol', 'Local'],
            ['Geely', 'Coolray', 'SUV', 1500, 1100, 2020, 'Petrol', 'Local'],
            ['Haval', 'Jolion', 'SUV', 1500, 1300, 2022, 'Petrol', 'Imported'],
            ['Mazda', 'Mazda3', 'Sedan', 1600, 1500, 2010, 'Petrol', 'Imported'],
            ['Mazda', 'CX-5', 'SUV', 2000, 2100, 2014, 'Petrol', 'Imported'],
            ['Lada', 'Granta', 'Sedan', 1600, 450, 2019, 'Petrol', 'Local'],
            ['Isuzu', 'D-Max', 'Pickup', 3000, 1900, 2014, 'Diesel', 'Imported'],
        ];
    }

    public static function featurePool(): array
    {
        return [
            'Air conditioning', 'Power windows', 'Central locking', 'ABS brakes', 'Airbags', 'Alloy wheels',
            'Rear parking sensors', 'Reverse camera', 'Cruise control', 'Bluetooth', 'Touch screen',
            'Apple CarPlay / Android Auto', 'Push start', 'Keyless entry', 'Sunroof', 'Leather seats',
            'Heated seats', 'Dual-zone climate', 'LED headlights', 'Fog lights', 'Roof rails', 'Tow bar',
        ];
    }
}
