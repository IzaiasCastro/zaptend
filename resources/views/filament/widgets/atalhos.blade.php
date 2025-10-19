<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-10">
            <ul class="flex flex-col gap-15">
                <li>
                    <x-filament::link 
                        :href="'/admin/agendamentos'" 
                        :active="request()->routeIs('filament.resources.agendamentos.*')"
                        icon="heroicon-s-calendar"
                    >
                        Agendamentos
                    </x-filament::link>
                </li>

                <li>
                    <x-filament::link 
                        :href="'/admin/agendas'" 
                        :active="request()->routeIs('filament.resources.agendas.*')"
                        icon="heroicon-s-calendar-days"
                    >
                        Agendas
                    </x-filament::link>
                </li>

                <li>
                    <x-filament::link 
                        :href="'/admin/profissionals'" 
                        :active="request()->routeIs('filament.resources.profissionais.*')"
                        icon="heroicon-s-user-group"
                    >
                        Profissionais
                    </x-filament::link>
                </li>

                <li>
                    <x-filament::link 
                        :href="'/admin/clientes'" 
                        :active="request()->routeIs('filament.resources.clientes.*')"
                        icon="heroicon-s-users"
                    >
                        Clientes
                    </x-filament::link>
                </li>

                <li>
                    <x-filament::link 
                        :href="'/admin/servicos'" 
                        :active="request()->routeIs('filament.resources.servicos.*')"
                        icon="heroicon-s-cog"
                    >
                        Serviços
                    </x-filament::link>
                </li>
            </ul>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
