<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class UsersController extends Controller
{
  public function arrayToString($delimiter, $array)
  {
    return implode($delimiter, $array);
  }

  public function stringToArray($delimiter, $string)
  {
    return explode($delimiter, $string);
  }

  public function index()
  {
    $users = User::all();
    return response()->json(['data' => $users]);
  }


  public function store(Request $request)
  {
    $email =  $request->input('email');
    $user =  User::where('email', $email)->first();
    if ($user) {
      return response()->json(['message' => "User with email: {$email} exists", 'data' => $user], 200);
    } else {
      $user = new User;
      $user->name = $request->input('name');
      $user->email = $email;
      $user->picture = $request->input('picture');
      $user->verified_email = $request->input('verified_email');
      if ($request->has('favourite_albums')) {
        $user->favourite_albums = $this->arrayToString(',,,', $request->input('favourite_albums'));
      }
      if ($request->has('favourite_artists')) {
        $user->favourite_artists = $this->arrayToString(',,,', $request->input('favourite_artists'));
      }

      $user->save();

      return response()->json(['data' => $user], 201);
    }
  }

  public function show(Request $request)
  {
    $user =  User::where('email', $request->query('email'))->first();;

    if (!$user) {
      return response()->json(['message' => 'User not found'], 404);
    }
    return response()->json(['data' => $user]);
  }

  public function update(Request $request)
  {
    $email = $request->input('email');
    $user =  User::where('email', $email)->first();;
    if (!$user) {
      return response()->json(['message' => 'User not found'], 404);
    }

    $type = $request->input('type');
    $action = $request->input('action');
    $url = $request->input('url');

    $arr = $this->stringToArray(',,,', $user->{"favourite_{$type}s"});
    if ($action == 'add') {
      if (!in_array($url, $arr)) {
        // If it's not, add it to the end of the array
        array_push($arr, $url);
      } else {
        return response()->json(['message' => "{$type} already in favourites!"], 400);
      }
    } else {
      // remove
      if (in_array($url, $arr)) {
        // If it's, remove it from the array
        $key = array_search($url, $arr); // get the index of the element to remove
        if ($key !== false) { // check if the element exists in the array
          unset($arr[$key]); // remove the element using the index
        }
      } else {
        return response()->json(['message' => "{$type} not found in favourites!"], 400);
      }
    }

    if (count($arr) > 0 && empty($arr['1'])) {
      $new_str = $arr['2'];
    } else {
      $new_str = $this->arrayToString(',,,', $arr);
    }
    $user->{"favourite_{$type}s"} = $new_str;
    $user->save();
    return response()->json(['data' => $new_str], 200);
  }

  public function destroy(Request $request)
  {
    $user =  User::where('email', $request->input('email'))->first();;
    if (!$user) {
      return response()->json(['message' => 'User not found'], 404);
    }
    $user->delete();
    return response()->json(['message' => 'User deleted']);
  }
}
