<template>
    <a-layout style="min-height: 100vh">
        <a-layout-sider v-model:collapsed="collapsed" collapsible :width="250">
            <div class="logo" />
            <a-menu v-model:selectedKeys="selectedKeys" @click="handleClick" :default-selected-keys="['products']"
                :default-open-keys="['sub1']" theme="dark" mode="inline">

                <a-menu-item key="/projects">
                    <dashboard-outlined />
                    <span>Проекты</span>
                </a-menu-item>
                <a-menu-item key="/">
                    <OrderedListOutlined />
                    <span>Расчеты</span>
                </a-menu-item>
                <a-menu-item key="/computing-resources">
                    <dashboard-outlined />
                    <span>Вычислительные ресурсы</span>
                </a-menu-item>
                <a-menu-item key="/web-widgets">
                    <WindowsOutlined />
                    <span>Виджеты</span>
                </a-menu-item>
                <a-menu-item key="/microservices">
                    <CloudServerOutlined />
                    <span>Микросервисы</span>
                </a-menu-item>
                <a-menu-item key="/calculation-cases">
                    <FileZipOutlined />
                    <span>Расчетные кейсы</span>
                </a-menu-item>


                <!-- <a-sub-menu key="/debit-debts">
                    <template #title>
                        <span>
                            <ShopOutlined />
                            <span>З</span>
                        </span>
                    </template>
<a-menu-item key="/catalog">Список</a-menu-item>
<a-menu-item key="/catalog/add">Добавить</a-menu-item>
</a-sub-menu> -->
            </a-menu>
        </a-layout-sider>
        <a-layout>
            <a-layout-header
                style="background: #ffffff; border-bottom: 1px solid #ddd; padding-right: 18px; display: flex; justify-content: space-between;">
                <div style="display: flex; ">
                    <p v-if="ComputingResourceStatus[resourceStatus] === ComputingResourceStatus.ONLINE">Сервер
                        оркестрации
                        процессов: <a-tag color="green">{{ resourceStatus }}</a-tag></p>
                    <p v-if="ComputingResourceStatus[resourceStatus] === ComputingResourceStatus.OFFLINE">Сервер
                        оркестрации
                        процессов: <a-tag color="red">{{ resourceStatus }}</a-tag></p>
                </div>
                <div style="display: flex; margin-top: 16px;">
                    <a-button v-if="userViewer">
                        <template #icon>
                            <UserOutlined />
                        </template>
                        {{ userViewer.email }}
                    </a-button>
                    <DownOutlined />


                    <a-button style="margin-left: 8px;" @click="() => onLogoutClick()">
                        Выйти
                    </a-button>
                    <DownOutlined />


                </div>
            </a-layout-header>
            <a-layout-content style="margin: 0 16px">
                <!-- <a-breadcrumb style="margin: 16px 0">
                    <a-breadcrumb-item>User</a-breadcrumb-item>
                    <a-breadcrumb-item>Bill</a-breadcrumb-item>
                </a-breadcrumb> -->
                <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
                    <slot />
                </div>
            </a-layout-content>
            <a-layout-footer style="text-align: center">
                <!-- <a :href="`${config.public.MAIN_WEBSITE_URL}`" target="_blank">everigin.com</a> -->
            </a-layout-footer>
        </a-layout>

    </a-layout>
</template>
<script lang="ts">
import { defineComponent, computed } from 'vue';

import { ViewerModel } from "~/src/entities/viewer";
import { ComputingResourceStatus } from "~/src/shared/types/index.ts"

import { OrchestratorServerAvailableModel } from "~/src/entities/orchestrator-server-available";

import {
    PieChartOutlined,
    DesktopOutlined,
    TeamOutlined,
    FileOutlined,
    DashboardOutlined,
    UserOutlined,
    ShopOutlined,
    WalletOutlined,
    InfoCircleOutlined,
    CalculatorOutlined,
    OrderedListOutlined,
    CloudServerOutlined,
    WindowsOutlined,
    FileZipOutlined,
} from '@ant-design/icons-vue';

export default defineComponent({
    components: {
        PieChartOutlined,
        DesktopOutlined,
        UserOutlined,
        TeamOutlined,
        FileOutlined,
        DashboardOutlined,
        ShopOutlined,
        WalletOutlined,
        InfoCircleOutlined,
        CalculatorOutlined,
        OrderedListOutlined,
        CloudServerOutlined,
        WindowsOutlined,
        FileZipOutlined,
    },
    setup() {
        const config = useRuntimeConfig()
        const { logout } = ViewerModel.useStore();
        const { fetch } = ViewerModel.useComposable()
        const { orchestratorServerAvailable, isLoading, error } = OrchestratorServerAvailableModel.useComposable();

        const router = useRouter()

        const { result, loading } = fetch(10000)

        const userViewer = computed(() => {
            let user = null

            try {
                user = result.value.userViewer
            } catch (error) {
                console.error(error);
            }

            return user
        });

        const collapsed = ref(false)
        const selectedKeys = ref(['catalog'])

        const handleClick = (data: any) => {
            router.push(data.key)
        }

        const resourceStatus = computed(() => {
            return orchestratorServerAvailable.value.status as ComputingResourceStatus
        })

        const onLogoutClick = async (values: any) => {
            await logout()
            navigateTo("/auth");
        };

        return {
            config,
            collapsed,
            selectedKeys,
            handleClick,
            onLogoutClick,
            resourceStatus,
            ComputingResourceStatus,
            userViewer,
        }
    },
});
</script>
<style>
#components-layout-demo-custom-trigger .trigger {
    font-size: 18px;
    line-height: 64px;
    padding: 0 24px;
    cursor: pointer;
    transition: color 0.3s;
}

#components-layout-demo-custom-trigger .trigger:hover {
    color: #1890ff;
}

#components-layout-demo-custom-trigger .logo {
    height: 32px;
    background: rgba(255, 255, 255, 0.3);
    margin: 16px;
}

.site-layout .site-layout-background {
    background: #fff;
}

#__nuxt {
    min-height: 100vh;
}
</style>