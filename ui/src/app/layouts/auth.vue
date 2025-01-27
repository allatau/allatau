<template>
    <a-layout style="min-height: 100vh">

        <a-layout>

            <a-layout-content style="margin: 0 16px">

                <div
                    :style="{ padding: '24px', background: '#fff', minHeight: '360px', display: 'flex', justifyContent: 'center' }">
                    <slot />
                </div>
            </a-layout-content>

        </a-layout>
    </a-layout>
</template>
<style>
#components-layout-demo-side .logo {
    height: 32px;
    margin: 16px;
    background: rgba(255, 255, 255, 0.3);
}

.site-layout .site-layout-background {
    background: #fff;
}

[data-theme='dark'] .site-layout .site-layout-background {
    background: #141414;
}
</style>