<?php


namespace App\Http\Controllers;


use App\Models\Cities;
use App\Models\OtherUsers;
use App\Models\Queue;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class MainController extends Controller
{

    public function getInfoUser($queueId)
    {
        $queue = Queue::query()->findOrFail($queueId);
        if (!$queue->user_id) {
            return response()->json(['msg' => 'В обработке'], 102);
        }
        /** @var User $user */
        $user = User::query()->findOrFail($queue->user_id);

        $others = OtherUsers::query()
            ->where('queue_id', '=', $queue->id)->get();
        $mass = [];
        foreach ($others as $other) {
            $content = [];
            $userOther = User::query()->findOrFail($other->user_id);
            $content[] = [
                'fio' => $userOther->surname . ' ' . $userOther->first_name . ' ' . $userOther->patronymic,
                'email' => $userOther->email,
                'date_beth' => $userOther->date_beth,
                'city' => $userOther->city->name,
                'inn' => $userOther->inn,
                'percent' => $other->percent,
                'url' => asset('storage/dataset/' . $userOther->path),
            ];
            $mass[] = $content;
        }

        return response()->json([
            'fio' => $user->surname . ' ' . $user->first_name . ' ' . $user->patronymic,
            'email' => $user->email,
            'date_beth' => $user->date_beth,
            'city' => $user->city->name,
            'inn' => $user->inn,
            'percent' => $queue->percent,
            'url' => asset('storage/dataset/' . $user->path),
            'similar' => $mass
        ], 200);
    }

    public function storeUserIdentity(Request $request)
    {
        DB::beginTransaction();
        $user = new User();
        $stroka = $request->get('fio');
        $split = explode(' ', $stroka);
        $user->surname = isset($split[0]) ? $split[0]: null;
        $user->name = $request->get('ema');
        $user->first_name = isset($split[1]) ? $split[1]: null;
        $user->patronymic = isset($split[2]) ? $split[2]: null;
        $user->email = $request->get('ema');
        $user->password = Hash::make($request->get('password'));
        $user->inn = $request->get('inn');
        $user->date_beth = $request->get('dateBeth');
        $city = new Cities();
        $city->code = $request->get('city');
        $city->name = $request->get('city');
        $city->save();
        $user->city_id = $city->id;

        if (!$user->save()) {
            DB::rollBack();
            throw new HttpResponseException(response()->json('Не удалось сохранить данные', 500));
        }
        if (!$request->hasFile('photo')) {
            DB::rollBack();
            throw new HttpResponseException(response()->json('Не обходимо прикрепить файл', 400));
        }
        $file = $request->file('photo');
        $user->path = $user->id . '.' . $file->extension();
        $user->save();
        $file->storeAs('public/dataset/', $user->path);

        DB::commit();
        return response()->json([
            'msg' => 'Пользователь добавлен'
        ], 201);
    }

    public function searchUser(Request $request)
    {
        if (!$request->hasFile('photo')) {
            throw new HttpResponseException(response()->json('Не обходимо прикрепить файл', 400));
        }

        $file = $request->file('photo');
        $fileName = time() . '.' . $file->extension();
        $file->storeAs('public/find-data/', $fileName);
        $queue = new Queue();
        $queue->img_path = $fileName;
        $queue->save();
        if (!$queue->save()) {
            throw new HttpResponseException(response()->json('Не удалось сохранить данные', 500));
        }

        return response()->json([
            'queueId' => $queue->id,
        ], 201);
    }

    public function getCities()
    {
        return response()->json(Cities::all(), 200);
    }

}
