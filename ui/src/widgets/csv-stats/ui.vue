<template>
    <div style="background: #ececec; padding: 30px" v-if="isCsvExist">
        <h2>Остатки от поставщика и для маркетов</h2>
        <a-button @click="loadData()" :loading="isLoading">Загрузить данные</a-button>
        <a-row v-if="dataSourceInit.count != 0" :gutter="16" style="margin-top: 16px">
            <a-col :span="8">
                <a-card>
                    <a-statistic title="Всего товаров" :value="dataSourceInit.count" style="margin-right: 50px">
                        <template #prefix>

                            <arrow-down-outlined />
                        </template>
                    </a-statistic>
                </a-card>
            </a-col>
            <a-col :span="8">
                <a-card>
                    <a-statistic title="Остатки от поставщика" :value="dataSourceInit.nonZeroCountByProducer"
                        class="demo-class">
                        <template #prefix>
                            <arrow-down-outlined />
                        </template>
                    </a-statistic>
                </a-card>
            </a-col>
            <a-col :span="8">
                <a-card>
                    <a-statistic title="Передаются на маркет" :value="dataSourceInit.nonZeroCount" class="demo-class">
                        <template #prefix>
                            <arrow-up-outlined />
                        </template>
                    </a-statistic>
                </a-card>
            </a-col>
        </a-row>
        <a-skeleton v-else :active="isLoading" style="margin-top: 16px" />
    </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref, watch, onMounted } from 'vue';

import { useCsvLoader } from "./model"
import { ExporterEnum, ExporterEnumKey } from "~/src/shared/types"
import { ArrowUpOutlined, ArrowDownOutlined } from '@ant-design/icons-vue';

import { SdekCalculate } from "~/src/features/sdek-calculate"


import { useLoaderState } from "~/src/shared/lib"

export default defineComponent({
    props: {
        jsonData: {
            type: Object,
            required: true,
        },

    },
    components: {
        SdekCalculate,
        ArrowUpOutlined,
        ArrowDownOutlined
    },
    setup(props) {
        const activeKey = ref([]);

        const { enableError, enableLoading, enableLoaded, isLoaded, isLoading, isError } = useLoaderState();

        const title = ref("")
        const url = ref<string>("")

        const skuField = ref("")

        const dataResult = ref([])

        const { fetch, data: dataSourceInit, errorText } = useCsvLoader();



        const postProcessors = computed(() => {
            let data = [];

            const first_key = Object.keys(props.jsonData)[0]

            const item = props.jsonData[first_key]

            if (item.post_processors) {
                data = item.post_processors
            }

            return data
        })

        const exportProcessors = computed(() => {
            let data = [];

            const first_key = Object.keys(props.jsonData)[0]

            const item = props.jsonData[first_key]

            if (item.export_processors) {
                data = item.export_processors
            }

            return data
        })

        const loadData = async () => {

            enableLoading()

            await fetch(url.value as any)
            enableLoaded()
        }

        const convertLink = (moduleName: ExporterEnumKey, title_var: string): string => {
            let format: ExporterEnum = ExporterEnum["export_processors.csv_default_exporter"];

            format = ExporterEnum[moduleName];

            return `https://yml.north-sun.ru/media/${title_var}.${format}`
        }

        const isCsvExist = computed(() => {

            let status = false;
            for (let index = 0; index < exportProcessors.value.length; index++) {
                if (ExporterEnum[exportProcessors.value[index].module as ExporterEnumKey] === ExporterEnum["export_processors.csv_default_exporter"]) {
                    status = true
                }
            }

            return status
        })




        watch(activeKey, val => {
            console.log(val);
        });


        onMounted(() => {
            title.value = Object.keys(props.jsonData)[0]
            url.value = convertLink('export_processors.csv_default_exporter' as ExporterEnumKey, title.value)

        })


        return {
            activeKey,

            dataResult,
            postProcessors,
            exportProcessors,
            title,
            convertLink,
            dataSourceInit,
            loadData,
            enableError,
            enableLoading,
            isLoaded, isLoading, isError,
            skuField,
            url,
            isCsvExist,
            errorText
        };
    }
});
</script>