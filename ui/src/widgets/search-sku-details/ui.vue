<template>
    <div v-if="isCsvAnalysisExist">
        <div style="display: flex; justify-content: space-between;">
            <div style="display: flex;flex-direction: column;">
                <h2 style="display: flex;padding: 20px 20px 0px 20px">Получение данных расчета по SKU</h2>
                <p style="display: flex;padding: 0px 20px 0px 20px">Нужно вводить sku, который предоставляет поставщик, без
                    прфиксов</p>

                <div style="display: flex;align-items: center;justify-content: space-between;padding: 10px 20px 20px 20px">
                    <div style="display: flex;">
                        <a-input :placeholder="`Введите sku`" style="max-width: 200px" v-model:value="skuField" />
                        <a-button style="margin-left: 8px" @click="loadData()">Поиск</a-button>

                        <div style="display: flex;margin-top: 4px;margin-left: 14px;">
                            <a-spin :spinning="isLoading === true" />
                        </div>


                    </div>

                </div>
                <p style="display: flex;margin-left: 20px;color: red;" :href="`${url}`">{{ errorText }}</p>
                <a style="display: flex;margin-left: 20px" :href="`${url}`" target="_blank">{{ url }}</a>
            </div>
            <div style="height: 180px;">

                <a-spin tip="Ожидается поиск..." :spinning="false">
                    <SdekCalculate :width="dataSourceForCalcultion.width" :length="dataSourceForCalcultion.length"
                        :height="dataSourceForCalcultion.height" :weight="dataSourceForCalcultion.weight" />
                </a-spin>
            </div>
            <div style="height: 220px;">
                <a-spin tip="Ожидается поиск..." :spinning="false">
                    <CalculateEndCost :Z="dataSourceForCalcultion.wholesale_price"
                        :D="dataSourceForCalcultion.delivery_cost_for_one_quantity"
                        :N="dataSourceForCalcultion.markup_koef" />
                </a-spin>
            </div>
        </div>



        <a-table v-if="dataSource.length > 0" :pagination="false" :columns="columns" :data-source="dataSource"
            :scroll="{ x: 1500 }">
            <template #bodyCell="{ column, record, index, text }">

                <template v-if="column.key === 'num' && record['num'] != 0">

                    <slot name="processorRowRender" :text="text" :record="record" :index="index" :column="column">{{
                        record['num'] }}</slot>

                </template>

            </template>
        </a-table>
    </div>
</template>

<script lang="ts">
import { defineComponent, computed, ref, watch, onMounted } from 'vue';

import { useCsvAnalysisLoader } from "./model";
import { ExporterEnum, ExporterEnumKey } from "~/src/shared/types"


import { SdekCalculate } from "~/src/features/sdek-calculate"
import { CalculateEndCost } from "~/src/features/calculate-end-cost"

import { useLoaderState } from "~/src/shared/lib"

export default defineComponent({
    props: {
        jsonData: {
            type: Object,
            required: true,
        },
        isExpandedRowRenderDisabled: {
            type: Boolean,
            default: false,
        },
    },
    components: {
        SdekCalculate,
        CalculateEndCost,
    },
    setup(props) {
        const activeKey = ref([]);

        const { enableError, enableLoading, enableLoaded, isLoaded, isLoading, isError } = useLoaderState();

        const title = ref("")
        const url = ref<string>("")

        const skuField = ref("")

        const dataResult = ref([])

        const { fetch, data: dataSourceInit, errorText } = useCsvAnalysisLoader();

        const dataSource = computed(() => {
            return dataSourceInit.value.map((item: any, index: any) => {
                return {
                    ...item,
                    num: index
                }
            })
        })


        const dataSourceForCalcultion = computed(() => {

            let d = {
                width: 10,
                height: 8,
                length: 6,
                weight: 4,
                wholesale_price: 1000,
                delivery_cost_for_one_quantity: 250,
                markup_koef: 0.2

            }

            try {
                d = {
                    width: dataSource.value[dataSource.value.length - 1]["width"],
                    height: dataSource.value[dataSource.value.length - 1]["height"],
                    length: dataSource.value[dataSource.value.length - 1]["length"],
                    weight: dataSource.value[dataSource.value.length - 1]["weight"],

                    wholesale_price: dataSource.value[dataSource.value.length - 1]["wholesale_price"],
                    delivery_cost_for_one_quantity: dataSource.value[dataSource.value.length - 1]["delivery_cost_for_one_quantity"],
                    markup_koef: dataSource.value[dataSource.value.length - 1]["markup_koef"] - 1,
                }
            } catch (error) {

            }

            return d
        })

        const columns = computed(() => {
            let arr: any = []
            try {
                arr = [
                    {
                        title: "num",
                        dataIndex: "num",
                        key: "num",
                    },
                    ...Object.keys(dataSourceInit.value[0]).filter((x) => ["price_without_delivery_cost", "gtin"].includes(x) == false).map((x) => {
                        return {
                            title: x,
                            dataIndex: x,
                            key: x,
                        }
                    })

                ]


            } catch (error) {

            }
            return arr
        })

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

            await fetch(url.value as any, skuField.value)
            enableLoaded()
        }

        const convertLink = (moduleName: ExporterEnumKey, title_var: string): string => {
            let format: ExporterEnum = ExporterEnum["export_processors.xml_default_exporter"];

            format = ExporterEnum[moduleName];

            return `https://yml.north-sun.ru/media/${title_var}.${format}`
        }

        const isCsvAnalysisExist = computed(() => {

            let status = false;
            for (let index = 0; index < exportProcessors.value.length; index++) {
                if (ExporterEnum[exportProcessors.value[index].module as ExporterEnumKey] === ExporterEnum["export_processors.csv_analysis_exporter"]) {
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
            url.value = convertLink('export_processors.csv_analysis_exporter', title.value)

        })


        return {
            activeKey,
            columns,
            dataResult,
            postProcessors,
            exportProcessors,
            title,
            convertLink,
            loadData,
            enableError,
            enableLoading,
            isLoaded, isLoading, isError,
            dataSource,
            skuField,
            url,
            isCsvAnalysisExist,
            dataSourceForCalcultion,
            errorText
        };
    }
});
</script>