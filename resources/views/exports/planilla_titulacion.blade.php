
<table>
    <thead>
        {{-- Fila 1: Título principal (Lista de Chequeo - Azul) --}}
        <tr>
            <th colspan="{{ 2 + array_sum(array_column($data['categorias'], 'colspan')) + 2 }}" style="vertical-align: middle; background-color: {{ $data['lista_chequeo']['color'] }}; color: #ffffff; text-align: center; font-weight: bold; font-size: 12px; height: 30px; border: 1px solid #000000;">
                {{ $data['lista_chequeo']['nombre'] }}
            </th>
        </tr>
        
        {{-- Fila 2: Categorías (Amarillo) --}}
        <tr>
            <th colspan="2" rowspan="3" style="vertical-align: middle; background-color: #FFFFFF; text-align: center; font-weight: bold; border: 1px solid #000000;"></th>
            @foreach($data['categorias'] as $categoria)
                <th colspan="{{ $categoria['colspan'] }}" style="vertical-align: middle; background-color: {{ $categoria['color'] }}; text-align: center; font-weight: bold; border: 1px solid #000000;">
                    {{ $categoria['nombre'] }}
                </th>
            @endforeach
            <th style="vertical-align: middle; background-color: #92D050; text-align: center; font-weight: bold; border: 1px solid #000000;">Responsable</th>
            <th style="vertical-align: middle; background-color: #92D050; text-align: center; font-weight: bold; border: 1px solid #000000;">Responsable</th>
        </tr>
        
        {{-- Fila 3: Etiquetas (Gris) --}}
        <tr>
            @foreach($data['categorias'] as $categoria)
                @foreach($categoria['etiquetas'] as $etiqueta)
                    <th colspan="{{ $etiqueta['colspan'] }}" style="vertical-align: middle; background-color: {{ $etiqueta['color'] }}; text-align: center; font-weight: bold; border: 1px solid #000000;">
                        {{ $etiqueta['nombre'] }}
                    </th>
                @endforeach
            @endforeach
            <th rowspan="3" style="vertical-align: middle; background-color: #FFFFFF; text-align: center; font-weight: bold; border: 1px solid #000000;">Firma Operario</th>
            <th rowspan="3" style="vertical-align: middle; background-color: #92D050; text-align: center; font-weight: bold; border: 1px solid #000000;">Firma Líder U.V.A</th>
        </tr>
        
        {{-- Fila 4: Información adicional de etiquetas (Gris) --}}
        <tr>
            @foreach($data['categorias'] as $categoria)
                @foreach($categoria['etiquetas'] as $etiqueta)
                    <th colspan="{{ $etiqueta['colspan'] }}" style="vertical-align: middle; background-color: {{ $etiqueta['color'] }}; text-align: center; font-weight: bold; border: 1px solid #000000;">
                        {{ $etiqueta['info_adicional'] }}
                    </th>
                @endforeach
            @endforeach
        </tr>
        
        {{-- Fila 5: Preguntas (Naranja) --}}
        <tr>
            <th style="vertical-align: middle; background-color: #fc9425; text-align: center; font-weight: bold; border: 1px solid #000000;">Fecha</th>
            <th style="vertical-align: middle; background-color: #fc9425; text-align: center; font-weight: bold; border: 1px solid #000000;">Hora de titulación</th>
            @foreach($data['categorias'] as $categoria)
                @foreach($categoria['etiquetas'] as $etiqueta)
                    @foreach($etiqueta['preguntas'] as $pregunta)
                        <th style="vertical-align: middle; background-color: {{ $pregunta['color'] }}; text-align: center; font-weight: bold; border: 1px solid #000000;">
                            {{ $pregunta['nombre'] }}
                        </th>
                    @endforeach
                @endforeach
            @endforeach
        </tr>
    </thead>
    <tbody>
        @if(isset($data['registros']) && count($data['registros']) > 0)
            @foreach($data['registros'] as $registro)
                <tr>
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;">{{ $registro['fecha'] }}</td>
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;">{{ $registro['hora'] }}</td>
                    @foreach($data['categorias'] as $categoria)
                        @foreach($categoria['etiquetas'] as $etiqueta)
                            @foreach($etiqueta['preguntas'] as $pregunta)
                                <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;">
                                    {{ $registro['respuestas'][$pregunta['id']] ?? '' }}
                                </td>
                            @endforeach
                        @endforeach
                    @endforeach
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;">{{ $registro['firma_operario'] ?? '' }}</td>
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;">{{ $registro['firma_lider'] ?? '' }}</td>
                </tr>
            @endforeach
        @else
            {{-- Filas vacías si no hay datos --}}
            @for($i = 0; $i < 15; $i++)
                <tr>
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;"></td>
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;"></td>
                    @foreach($data['categorias'] as $categoria)
                        @foreach($categoria['etiquetas'] as $etiqueta)
                            @foreach($etiqueta['preguntas'] as $pregunta)
                                <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;"></td>
                            @endforeach
                        @endforeach
                    @endforeach
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;"></td>
                    <td style="vertical-align: middle; text-align: center; border: 1px solid #000000;"></td>
                </tr>
            @endfor
        @endif
    </tbody>
</table>
