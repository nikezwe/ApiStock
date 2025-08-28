<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthController extends Controller
{
    /**
     * Inscription d’un nouvel utilisateur
     */
    public function register(Request $request)
    {
        try {
            // Validation mise à jour pour ne pas inclure "first_name" et "last_name"
            $data = $request->validate([
                'username'  => 'required|string',
                'email'     => 'required|email|unique:users',
                'password'  => 'required|min:6',
            ]);

            $user = User::create([
                'username'  => $data['username'],
                'email'     => $data['email'],
                'password'  => bcrypt($data['password']),
            ]);

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'user'  => $user,
                'token' => $token
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de l’inscription',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Connexion utilisateur
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email'    => 'required|email',
                'password' => 'required'
            ]);

            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Identifiants invalides'], 401);
            }

            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'user'  => $user,
                'token' => $token
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de la connexion',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Déconnexion utilisateur
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json(['message' => 'Déconnecté avec succès'], 200);

        } catch (Exception $e) {
            return response()->json([
                'error'   => 'Erreur lors de la déconnexion',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
