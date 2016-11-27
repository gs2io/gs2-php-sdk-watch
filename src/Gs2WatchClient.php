<?php
/*
 Copyright Game Server Services, Inc.

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 */

namespace GS2\Watch;

use GS2\Core\Gs2Credentials as Gs2Credentials;
use GS2\Core\AbstractGs2Client as AbstractGs2Client;
use GS2\Core\Exception\NullPointerException as NullPointerException;

/**
 * GS2-Timer クライアント
 *
 * @author Game Server Services, inc. <contact@gs2.io>
 * @copyright Game Server Services, Inc.
 *
 */
class Gs2WatchClient extends AbstractGs2Client {

	public static $ENDPOINT = 'watch';
	
	/**
	 * コンストラクタ
	 * 
	 * @param string $region リージョン名
	 * @param Gs2Credentials $credentials 認証情報
	 * @param array $options オプション
	 */
	public function __construct($region, Gs2Credentials $credentials, $options = []) {
		parent::__construct($region, $credentials, $options);
	}
	
	/**
	 * アラームリストを取得
	 * 
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* alarmId => アラームID
	 * 		* ownerId => オーナーID
	 * 		* name => アラーム名
	 * 		* description => 説明文
	 * 		* service => 監視対象サービス
	 * 		* serviceId => 監視対象サービスID
	 * 		* operation => 監視対象オペレーション
	 * 		* expression => 演算子
	 * 		* threshold => 閾値
	 * 		* notificationId => 通知ID
	 * 		* createAt => 作成日時
	 * 		* updateAt => 更新日時
	 * 		* status => ステータス
	 * 		* lastStatusChangeAt => 最終ステータス更新日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeAlarm($pageToken = NULL, $limit = NULL) {
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
					'Gs2Watch', 
					'DescribeAlarm', 
					Gs2WatchClient::$ENDPOINT, 
					'/alarm',
					$query);
	}
	
	/**
	 * アラームを作成<br>
	 * <br>
	 * GS2 内のスタティスティックスに対して監視を行うことができます。<br>
	 * クオータの消費量に対して閾値を設定し、閾値を超えた際に通知を出すことができます。<br>
	 * 
	 * @param array $request
	 * * name => アラーム名
	 * * description => 説明文
	 * * service => 監視対象サービス
	 * * serviceId => 監視対象サービスID
	 * * operation => 監視対象オペレーション
	 * * expression => 演算子(>=, >, <, <=)
	 * * threshold => 閾値
	 * * notificationId => 通知ID
	 * @return array
	 * * item
	 * 	* alarmId => アラームID
	 * 	* ownerId => オーナーID
	 * 	* name => アラーム名
	 * 	* description => 説明文
	 * 	* service => 監視対象サービス
	 * 	* serviceId => 監視対象サービスID
	 * 	* operation => 監視対象オペレーション
	 * 	* expression => 演算子
	 * 	* threshold => 閾値
	 * 	* notificationId => 通知ID
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 * 	* status => ステータス
	 * 	* lastStatusChangeAt => 最終ステータス更新日時
	 */
	public function createAlarm($request) {
		if(is_null($request)) throw new NullPointerException();
		$body = [];
		if(array_key_exists('name', $request)) $body['name'] = $request['name'];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		if(array_key_exists('service', $request)) $body['service'] = $request['service'];
		if(array_key_exists('serviceId', $request)) $body['serviceId'] = $request['serviceId'];
		if(array_key_exists('operation', $request)) $body['operation'] = $request['operation'];
		if(array_key_exists('expression', $request)) $body['expression'] = $request['expression'];
		if(array_key_exists('threshold', $request)) $body['threshold'] = $request['threshold'];
		if(array_key_exists('notificationId', $request)) $body['notificationId'] = $request['notificationId'];
		$query = [];
		return $this->doPost(
					'Gs2Watch', 
					'CreateAlarm', 
					Gs2WatchClient::$ENDPOINT, 
					'/alarm',
					$body,
					$query);
	}

	/**
	 * アラームを取得
	 * 
	 * @param array $request
	 * * alarmName => アラーム名
	 * @return array
	 * * item
	 * 	* alarmId => アラームID
	 * 	* ownerId => オーナーID
	 * 	* name => アラーム名
	 * 	* description => 説明文
	 * 	* service => 監視対象サービス
	 * 	* serviceId => 監視対象サービスID
	 * 	* operation => 監視対象オペレーション
	 * 	* expression => 演算子
	 * 	* threshold => 閾値
	 * 	* notificationId => 通知ID
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 * 	* status => ステータス
	 * 	* lastStatusChangeAt => 最終ステータス更新日時
	 */
	public function getAlarm($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('alarmName', $request)) throw new NullPointerException();
		if(is_null($request['alarmName'])) throw new NullPointerException();
		$query = [];
		return $this->doGet(
				'Gs2Watch',
				'GetAlarm',
				Gs2WatchClient::$ENDPOINT,
				'/alarm/'. $request['alarmName'],
				$query);
	}

	/**
	 * アラームを更新
	 * 
	 * @param array $request
	 * * alarmName => アラーム名
	 * * description => 説明文
	 * * expression => 演算子(>=, >, <, <=)
	 * * threshold => 閾値
	 * * notificationId => 通知ID
	 * @return array
	 * * item
	 * 	* alarmId => アラームID
	 * 	* ownerId => オーナーID
	 * 	* name => アラーム名
	 * 	* description => 説明文
	 * 	* service => 監視対象サービス
	 * 	* serviceId => 監視対象サービスID
	 * 	* operation => 監視対象オペレーション
	 * 	* expression => 演算子
	 * 	* threshold => 閾値
	 * 	* notificationId => 通知ID
	 * 	* createAt => 作成日時
	 * 	* updateAt => 更新日時
	 * 	* status => ステータス
	 * 	* lastStatusChangeAt => 最終ステータス更新日時
	 */
	public function updateAlarm($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('alarmName', $request)) throw new NullPointerException();
		if(is_null($request['alarmName'])) throw new NullPointerException();
		$body = [];
		if(array_key_exists('description', $request)) $body['description'] = $request['description'];
		if(array_key_exists('expression', $request)) $body['expression'] = $request['expression'];
		if(array_key_exists('threshold', $request)) $body['threshold'] = $request['threshold'];
		if(array_key_exists('notificationId', $request)) $body['notificationId'] = $request['notificationId'];
		$query = [];
		return $this->doPut(
				'Gs2Watch',
				'UpdateAlarm',
				Gs2WatchClient::$ENDPOINT,
				'/alarm/'. $request['alarmName'],
				$body,
				$query);
	}
	
	/**
	 * アラームを削除
	 * 
	 * @param array $request
	 * * alarmName => アラーム名
	 */
	public function deleteAlarm($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('alarmName', $request)) throw new NullPointerException();
		if(is_null($request['alarmName'])) throw new NullPointerException();
		$query = [];
		return $this->doDelete(
					'Gs2Watch', 
					'DeleteAlarm', 
					Gs2WatchClient::$ENDPOINT, 
					'/alarm/'. $request['alarmName'],
					$query);
	}

	/**
	 * アラームイベントリストを取得<br>
	 * <br>
	 * 過去にアラームが発生した履歴などを確認できます。<br>
	 *
	 * @param array $request
	 * * alarmName => アラーム名
	 * @param string $pageToken ページトークン
	 * @param integer $limit 取得件数
	 * @return array
	 * * items
	 * 	* array
	 * 		* eventId => アラームイベントID
	 * 		* alarmId => アラームID
	 * 		* type => イベントの種類
	 * 		* eventAt => イベント発生日時
	 * * nextPageToken => 次ページトークン
	 */
	public function describeAlarmEvent($request, $pageToken = NULL, $limit = NULL) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('alarmName', $request)) throw new NullPointerException();
		if(is_null($request['alarmName'])) throw new NullPointerException();
		$query = [];
		if($pageToken) $query['pageToken'] = $pageToken;
		if($limit) $query['limit'] = $limit;
		return $this->doGet(
				'Gs2Watch',
				'DescribeAlarmEvent',
				Gs2WatchClient::$ENDPOINT,
				'/alarm/'. $request['alarmName']. '/event',
				$query);
	}

	/**
	 * サービス名リストを取得<br>
	 * <br>
	 * アラームを設定する際に指定できるサービスの一覧を取得できます。<br>
	 *
	 * @return array サービス名リスト
	 */
	public function describeService() {
		$query = [];
		$result = $this->doGet(
				'Gs2Watch',
				'DescribeService',
				Gs2WatchClient::$ENDPOINT,
				'/service',
				$query);
		return $result['items'];
	}

	/**
	 * オペレーション名リストを取得<br>
	 * <br>
	 * アラームを設定する際に指定できるオペレーションの一覧を取得できます。<br>
	 *
	 * @param array $request
	 * * serviceName => サービス名
	 * @return array オペレーション名リスト
	 */
	public function describeOperation($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('serviceName', $request)) throw new NullPointerException();
		if(is_null($request['serviceName'])) throw new NullPointerException();
		$query = [];
		$result = $this->doGet(
				'Gs2Watch',
				'DescribeOperation',
				Gs2WatchClient::$ENDPOINT,
				'/service/'. $request['serviceName']. '/operation',
				$query);
		return $result['items'];
	}

	/**
	 * メトリクスを取得<br>
	 * <br>
	 * 過去のサービスの利用状況を取得します。<br>
	 * 
	 * @param array $request
	 * * serviceId => サービスID
	 * * operation => オペレーション名
	 * * begin => メトリクス取得開始日時(unixepoch)
	 * * end => メトリクス取得終了日時(unixepoch)
	 * * allowLongTerm => 7日以上の期間のデータを取得することを許可するか(OPTIONAL)
	 * * serviceId => サービスID
	 * @return array
	 * * items
	 * 	* array
	 * 		* timestamp => タイムスタンプ(YYYY-MM-DD HH:mm:SS)
	 * 		* value => 値
	 */
	public function getMetric($request) {
		if(is_null($request)) throw new NullPointerException();
		if(!array_key_exists('serviceId', $request)) throw new NullPointerException();
		if(is_null($request['serviceId'])) throw new NullPointerException();
		if(!array_key_exists('operation', $request)) throw new NullPointerException();
		if(is_null($request['operation'])) throw new NullPointerException();
		$query = [];
		if(array_key_exists('begin', $request)) $query['begin'] = $request['begin'];
		if(array_key_exists('end', $request)) $query['end'] = $request['end'];
		if(array_key_exists('allowLongTerm', $request)) $query['allowLongTerm'] = $request['allowLongTerm'];
		return $this->doGet(
				'Gs2Watch',
				'GetMetric',
				Gs2WatchClient::$ENDPOINT,
				'/metric/'. $request['serviceId']. '/'. $request['operation'],
				$query);
	}
	
}