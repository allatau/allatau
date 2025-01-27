import { decorate, injectable } from "inversify";
import "reflect-metadata";

import Papa from "papaparse";

// import { FeedConfigDTO } from "~/src/shared/types";

export class FeedCsvDetailsRepository {
  private async resolveLoad(
    url: string,
    sku_field_search: string
  ): Promise<any> {
    const data_temp: [] = [];
    let count = 0;
    return new Promise((resolve, reject) => {
      Papa.parse(url, {
        download: true,
        header: true,
        chunkSize: 300000000,
        step: function (results: any, parser: any) {
          if (sku_field_search == results.data.source_sku) {
            // console.log("Row data:", results.data);
            // console.log("Row errors:", results.errors);
            /* @ts-ignore */
            data_temp.push(results.data);
          }
          count = count + 1;
        },
        complete: function () {
          console.log("All done!");

          //   if (data_temp.length > 0) {
          //     message.success("Данные обновлены");
          //   } else {
          //     message.error("Не найдены данные по этому sku");
          //   }

          resolve(data_temp);
        },
        error: (error) => {
          reject("Вознили проблемы при выполнении запроса");
        },
      });
    });
  }

  public async fetchDetailsBySku(
    url: string,
    sku_field_search: string
  ): Promise<any> {
    return await this.resolveLoad(url as any, sku_field_search);
  }
}

decorate(injectable(), FeedCsvDetailsRepository);
