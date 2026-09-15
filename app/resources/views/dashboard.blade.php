@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6 pb-12">

        <div class="relative rounded-2xl overflow-hidden shadow-md bg-gray-900 min-h-[260px] flex items-end">
            <img src="{{ asset('images/colegio.jpg') }}" alt="Colegio"
                class="absolute inset-0 w-full h-full object-cover opacity-50">
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>

            <div class="relative p-8 text-white z-10">
                <h1 class="text-3xl font-bold leading-tight">
                    Los valores son amigos que en la vida te ayudan a ser feliz.
                </h1>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ asset('images/cardenal.jpg') }}" alt="Cardenal José Clemente Maurer"
                            class="w-20 h-24 object-cover rounded-lg border border-gray-200">
                        <div>
                            <h3 class="text-base font-bold text-[#7A1C1C]">Cardenal José Clemente Maurer</h3>
                            <p class="text-xs text-gray-400 font-medium">1900 - 1990</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-600 leading-relaxed text-justify">
                        José Clemente Maurer Clements, nació en Alemania en el poblado de El Sarré en el estado de
                        Puttlingen, el 13 de marzo de 1900 en una familia modesta de mineros, sus padres fueron Pedro Maurer
                        y Ángela Clements, sus hermanos fueron Meter y Susana. Sus medios hermanos eran Johann, Catarina y
                        Meter que fueron  los mayores, se bautizó el 18 de marzo en el templo parroquial de San Miguel.
                    </p>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <h4 class="text-xs font-bold tracking-wider text-[#7A1C1C] uppercase mb-2">Nuestra Misión</h4>
                        <p class="text-xs text-gray-600 leading-relaxed">
                            Crear y desarrollar procesos educativos de excelencia iluminados por la Pedagogía de Jesucristo,
                            en quien todos los valores humanos encuentran su plena realización.
                        </p>
                    </div>

                    <div class="bg-[#7A1C1C] text-white rounded-xl p-6">
                        <h4 class="text-xs font-bold tracking-wider text-white/90 uppercase mb-2">Nuestra Visión</h4>
                        <p class="text-xs text-gray-100 leading-relaxed">
                            Contribuir a la formación integral de las personas y comunidades, acorde con la perspectiva
                            liberadora del Evangelio.
                        </p>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                    <h4
                        class="text-xs font-bold tracking-wider text-[#7A1C1C] uppercase mb-4 border-b border-gray-100 pb-2">
                        Nuestros Valores</h4>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-xs">

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg text-[#7A1C1C] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Responsabilidad</p>
                                <p class="text-gray-500 text-[11px] mt-0.5">Cumplimiento consciente de nuestros deberes y
                                    compromisos.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg text-[#7A1C1C] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Respeto</p>
                                <p class="text-gray-500 text-[11px] mt-0.5">Valorar la dignidad propia y la de los demás sin
                                    distinción.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg text-[#7A1C1C] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Tolerancia</p>
                                <p class="text-gray-500 text-[11px] mt-0.5">Apertura y aceptación ante la diversidad de
                                    pensamiento.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg text-[#7A1C1C] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Gratitud</p>
                                <p class="text-gray-500 text-[11px] mt-0.5">Reconocimiento del bien recibido y aprecio por
                                    la vida.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg text-[#7A1C1C] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Honestidad</p>
                                <p class="text-gray-500 text-[11px] mt-0.5">Actuar con rectitud, transparencia y veracidad
                                    siempre.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 rounded-lg text-[#7A1C1C] shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 01-2 2h-disabled a2 2 0 01-2-2v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-bold text-gray-800">Innovación</p>
                                <p class="text-gray-500 text-[11px] mt-0.5">Búsqueda constante de mejores formas de aprender
                                    y crecer.</p>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm text-center max-w-2xl mx-auto">
            <p class="text-xs text-gray-700 italic">
                "Nunca consideres el estudio como una obligación, sino como una oportunidad para penetrar en el bello y
                maravilloso mundo del saber."
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-100 rounded-xl p-4 text-center border border-gray-200">
                <p class="text-2xl font-bold text-gray-800">1957</p>
                <span class="text-[10px] uppercase font-bold text-gray-500">Fundación</span>
            </div>
            <div class="bg-gray-100 rounded-xl p-4 text-center border border-gray-200">
                <p class="text-2xl font-bold text-[#7A1C1C]">{{ $totalEstudiantes }}</p>
                <span class="text-[10px] uppercase font-bold text-gray-500">Estudiantes</span>
            </div>
            <div class="bg-gray-100 rounded-xl p-4 text-center border border-gray-200">
                <p class="text-2xl font-bold text-[#7A1C1C]">{{ $totalMaestros }}</p>
                <span class="text-[10px] uppercase font-bold text-gray-500">Maestros</span>
            </div>
            <div class="bg-gray-100 rounded-xl p-4 text-center border border-gray-200">
                <p class="text-2xl font-bold text-gray-800">100%</p>
                <span class="text-[10px] uppercase font-bold text-gray-500">Compromiso</span>
            </div>
        </div>

    </div>
@endsection