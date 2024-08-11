<?php

namespace App\Http\Middleware;

use App\Models\Keluarga;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $keluarga_id = $request->route('keluarga_id');
        $keluarga = Keluarga::findOrFail($keluarga_id);
        if (!$keluarga['is_approved']) return response()->json([ 'status' => false, 'message' => 'Identitas Anda harus mendapat persetujuan dari petugas'], 403);
        return $next($request);
    }
}