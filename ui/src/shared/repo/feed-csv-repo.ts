import { decorate, injectable } from "inversify";
import "reflect-metadata";

import Papa from "papaparse";

export class FeedCsvRepository {
  private async resolveLoad(url: string): Promise<any> {
    const data_temp: [] = [];
    let count = 0;
    let nonZeroCount = 0;
    let nonZeroCountByProducer = 0;
    return new Promise((resolve) => {
      Papa.parse(url, {
        download: true,
        header: true,
        chunkSize: 300000000,
        step: function (results: any, parser: any) {
          if (Number(results.data.count) !== 0) {
            // console.log("Row data:", results.data);
            // console.log("Row errors:", results.errors);
            /* @ts-ignore */
            nonZeroCount = nonZeroCount + 1;
          }
          if (Number(results.data.count_by_producer) !== 0) {
            // console.log("Row data:", results.data);
            // console.log("Row errors:", results.errors);
            /* @ts-ignore */
            nonZeroCountByProducer = nonZeroCountByProducer + 1;
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

          resolve({
            count,
            nonZeroCount,
            nonZeroCountByProducer,
          });
        },
      });
    });
  }

  public async fetchListBySku(url: string): Promise<any> {
    return await this.resolveLoad(url as any);
  }
}

decorate(injectable(), FeedCsvRepository);
