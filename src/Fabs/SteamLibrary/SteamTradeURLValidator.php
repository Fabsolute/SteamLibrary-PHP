<?php

namespace Fabs\SteamLibrary;

use Fabs\SteamLibrary\Model\Cookie\SteamCookie;
use Fabs\SteamLibrary\Model\Escrow\TradeOfferEscrowModel;

class SteamTradeURLValidator
{
    /**
     * @param string $steam_partner_id
     * @param string $steam_token
     * @param SteamCookie $steam_cookie
     * @return bool
     */
    public function isValidForSending($steam_partner_id, $steam_token, $steam_cookie = null)
    {
        $trade_offer_escrow_model =
            $this->getEscrowFromPartnerIdAndToken($steam_partner_id, $steam_token, $steam_cookie);

        return
            $trade_offer_escrow_model !== null &&
            $trade_offer_escrow_model->error === null &&
            $trade_offer_escrow_model->days_their_escrow === 0;
    }

    /**
     * @param string $steam_partner_id
     * @param string $steam_token
     * @param SteamCookie $steam_cookie
     * @return bool
     */
    public function isValidForReceiving($steam_partner_id, $steam_token, $steam_cookie = null)
    {
        $trade_offer_escrow_model =
            $this->getEscrowFromPartnerIdAndToken($steam_partner_id, $steam_token, $steam_cookie);

        return
            $trade_offer_escrow_model !== null &&
            $trade_offer_escrow_model->error === null &&
            $trade_offer_escrow_model->days_my_escrow === 0;
    }

    /**
     * @param string $steam_partner_id
     * @param string $steam_token
     * @param SteamCookie $steam_cookie
     * @return bool
     */
    public function isValidForSendingAndReceiving($steam_partner_id, $steam_token, $steam_cookie = null)
    {
        $trade_offer_escrow_model =
            $this->getEscrowFromPartnerIdAndToken($steam_partner_id, $steam_token, $steam_cookie);

        return
            $trade_offer_escrow_model !== null &&
            $trade_offer_escrow_model->error === null &&
            $trade_offer_escrow_model->days_my_escrow === 0 &&
            $trade_offer_escrow_model->days_their_escrow === 0;
    }

    /**
     * @param string $steam_partner_id
     * @param string $steam_token
     * @param SteamCookie $steam_cookie
     * @return TradeOfferEscrowModel|null
     */
    public function getEscrowFromPartnerIdAndToken($steam_partner_id, $steam_token, $steam_cookie = null)
    {
        if ($steam_cookie !== null) {
            SteamRequest::$cookie =
                [
                    'sessionid' => $steam_cookie->session_id,
                    'steamLogin' => $steam_cookie->steam_login,
                    'steamLoginSecure' => $steam_cookie->steam_login_secure
                ];
        }

        $request_url = "https://steamcommunity.com/tradeoffer/new/?partner=${steam_partner_id}&token=${steam_token}";
        $response = SteamRequest::get($request_url, true, false);
        if ($response === null) {
            return null;
        }

        return $this->parseSteamResponse($response);
    }

    /**
     * @param string $response
     * @return TradeOfferEscrowModel|null
     */
    private function parseSteamResponse($response)
    {
        $matched_my = preg_match('/g_daysMyEscrow(?:[\s=]+)(?<days>[\d]+);/i', $response, $matches_my) === 1;
        $matched_their = preg_match('/g_daysTheirEscrow(?:[\s=]+)(?<days>[\d]+);/i', $response, $matches_their) === 1;
        if ($matched_my && $matched_their) {
            // Case : trade offer page
            $return_model = new TradeOfferEscrowModel();
            $return_model->days_my_escrow = intval($matches_my['days']);
            $return_model->days_their_escrow = intval($matches_their['days']);
            return $return_model;
        } else {
            // Case : other pages
            if (preg_match('/<div id="error_msg">(?<error_message>.+?)<\/div>/si', $response, $matches_error_message) === 1) {
                // Case trade url error page
                $error_message = $matches_error_message['error_message'];
                $error_message_clean = preg_replace('/\t|\n|\r/', '', $error_message);

                $return_model = new TradeOfferEscrowModel();
                $return_model->error = $error_message_clean;
                return $return_model;
            } else {
                // Case unknown page
                try {
                    file_put_contents('steam_response.html', $response);
                } catch (\Exception $exception) {
                }

                return null;
            }
        }
    }
}
