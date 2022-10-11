@props([
    'tileLayerUrl' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    'tileLayerOptions' => [
        'maxZoom' => 19,
        'attribution' => '© OpenStreetMap',
    ],
    'height' => '400px',
    'options' => [],
    'controls' => [],
    'markers' => [],
    'extraAttributeBag' => '',
    'extraAlpineAttributeBag' => '',
])

<div
    x-data="{
        map: null,
        markers: [],
        init: function () {
            this.map = L.map(this.$refs.map, {{ json_encode($options) }});
            L.tileLayer('{{ $tileLayerUrl }}', {{ json_encode($tileLayerOptions) }}).addTo(this.map);
            @foreach ($markers as $marker)
                this.addMarker('{{ $marker->getName()  }}',{{ $marker->getLat() }}, {{ $marker->getLng() }}, {{ json_encode($marker->getPopup()) }}, {{ trim($marker->getAction()) }});
            @endforeach
            @foreach($controls as $control)
                this.addControl({{ json_encode($control->getIcon()) }}, {{ trim($control->getAction()) }}, {{ json_encode($control->getLabel()) }}, {{ json_encode($control->getOptions()) }});
            @endforeach
        },
        addControl(icon, callback, label, options) {
            L.easyButton(icon, callback, label, options).addTo(this.map);
        },
        addMarker(id, lat, lng, info, callback) {
            const marker = L.marker([lat, lng]).addTo(this.map);
            if (info) {
                marker.bindPopup(info);
            }
            if (callback) {
                marker.on('click', callback);
            }
            this.markers.push({id, marker});
        },
        removeMarker(id) {
            const m = this.markers.find(m => m.id === id);
            if (m) {
                m.marker.remove();
                this.markers = this.markers.filter(m => m.id !== id);
            }
        },
        removeAllMarkers() {
            this.markers.forEach(({marker}) => marker.remove());
            this.markers = [];
        },
    }"
    {{ $attributes->class('h-full w-full overflow-hidden') }}
    {{ $extraAttributeBag }}
    {{ $extraAlpineAttributeBag }}>
        <div x-ref="map" class="flex-1 relative" style="width: 100%; height: {{ $height }}"></div>
</div>
