import { Container } from "inversify";
import "reflect-metadata";

import { FeedRepository } from "~/src/shared/repo/feed-repo";
import { FeedCsvDetailsRepository } from "~/src/shared/repo/feed-csv-details-repo";
import { FeedCsvRepository } from "~/src/shared/repo/feed-csv-repo";
import { FeedService } from "~/src/shared/services/feed-service";

export const TYPES = {
  FeedRepository: Symbol.for("FeedRepository"),
  FeedCsvDetailsRepository: Symbol.for("FeedCsvDetailsRepository"),
  FeedCsvRepository: Symbol.for("FeedCsvRepository"),
  FeedService: Symbol.for("FeedService"),
};

const container = new Container({ autoBindInjectable: false });

container.bind(TYPES.FeedRepository).to(FeedRepository).inSingletonScope();
container
  .bind(TYPES.FeedCsvDetailsRepository)
  .to(FeedCsvDetailsRepository)
  .inSingletonScope();
container
  .bind(TYPES.FeedCsvRepository)
  .to(FeedCsvRepository)
  .inSingletonScope();
container.bind(TYPES.FeedService).to(FeedService).inSingletonScope();

export { container };
