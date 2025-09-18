@extends('layouts.layout')

@section('title', 'Mozaik - Home')

@section('content')
    <h1>Üdvözöllek a könyvtárban</h1>
    <p>Kereshetsz könyveket és felfedezhetsz új csodákat.</p>
    <p>Vagy esetleg hozzáadnál új csodákat?</p>
    <p>Eme esetek valamelyikében, kérlek látogasd meg a <a href="{{ url('/konyvek') }}">Könyvek</a> szekciót igazi varázslatos kalandokért!</p>
    <img id="books" src="{{ asset('images/books.png') }}" alt="books">
@endsection
