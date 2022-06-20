<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\WorkingHours;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;

class indexController extends Controller
{
    public function getWorkingHours($date = '')
    {
        $date = ($date == '') ? date("Y-m-d") : $date;
        $returnArray = [];
        $hours = WorkingHours::all();
        foreach ($hours as $k => $v)
        {
            //saatin dolumu boşmu oldugunu kontrol ediyoruz
            $control = Appointment::where('date', $date)->where('WorkingHour',$v['id'])->count();
            $v['isActive'] = ($control == 0) ? true : false;
            $returnArray[] = $v;
        }
        return response()->json($returnArray);
    }

    public function appointmentStore(Request $request)
    {
        $returnArray = []; //geri dönüş arrayı
        $returnArray['status'] = "false"; //default değer
        $all = $request->except('_token'); //bu şekilde token hariç tüm verilerimizi alıyoruz.
        $control = Appointment::where('date',$all['date'])->where('workingHour',$all['workingHour'])->count();
        //üsteki kod seçilen tarihle ve şeçilen çalışma saatinde herhangi bir randevu var mı onun kontrolu yapılıyor
        if ($control != 0) //hani herhangi bir randevı varsa
        {
            $returnArray['message']= "Bu Randevu Tarihi Doludur";
            return response()->json($returnArray);//json olarak return arrayi geri döndürsün.
        }

        $create = Appointment::create($all); //randevuları oluşturuyoruz
        if ($create) //eğer randevu oluştuysa
        {
            $returnArray['status'] = true;
            $returnArray['message'] = "Randevunuz Başarı İle Alındı";
        }
        else
        {
            $returnArray['message'] = "Veri Eklenemedi Bizimle İletişime Geçiniz";
        }
        return response()->json($returnArray);
    }
}
