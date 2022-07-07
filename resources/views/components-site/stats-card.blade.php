<div class="stat">
    <div class="stat-title font-bold">{{ data_get($stat, 'name') }}</div>
    <div class="stat-desc mb-1">{{ data_get($stat, 'description') }}</div>
    <div class="stat-value">{{ number_format(data_get($stat, 'value'), 0) }}<span class="stat-desc ml-1">{{ data_get($stat, 'unit') }}</span></div>
</div>
