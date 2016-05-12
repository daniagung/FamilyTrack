package biz.prolike.hereiam.sms;


import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.telephony.SmsMessage;
import android.widget.Toast;

import com.tuenti.smsradar.Sms;
import com.tuenti.smsradar.SmsListener;
import com.tuenti.smsradar.SmsRadar;

import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;

import biz.prolike.hereiam.utils.DatabaseHandler;

public class SMSReceiver extends BroadcastReceiver {
    private  DatabaseHandler db;
    private SharedPreferences prefs;
    private Integer id_user;
    private Context mcontext;
    @Override
    public void onReceive(final Context context, Intent intent) {
        mcontext = context;
        db = new DatabaseHandler(context);
        prefs = context.getSharedPreferences("biz.prolike.hereiam", context.MODE_PRIVATE);
        id_user = Integer.parseInt(prefs.getString("id_user", ""));
        SmsRadar.initializeSmsRadarService(context, new SmsListener() {
            @Override
            public void onSmsSent(Sms sms) {
                DateFormat dateFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                dateFormatter.setLenient(false);
                Date today = new Date();
                String datemess = dateFormatter.format(today);
                // Show Alert
                int duration = Toast.LENGTH_LONG;
                if (prefs.getBoolean("child", true)) {
                    db.addSMS(sms.getAddress(), sms.getMsg(), "2", datemess, id_user);
                }
            }

            @Override
            public void onSmsReceived(Sms sms) {
            }
        });
        if (intent.getAction().equals("android.provider.Telephony.SMS_RECEIVED")){
            Bundle bundle = intent.getExtras();
            Object[] messages = (Object[]) bundle.get("pdus");
            SmsMessage[] sms = new SmsMessage[messages.length];

            for (int j=0; j < messages.length; j++) {
                sms[j] = SmsMessage.createFromPdu((byte[]) messages[j]);
            }

            for (SmsMessage msg : sms) {
                String body = msg.getDisplayMessageBody();
                String number = msg.getDisplayOriginatingAddress();
                DateFormat dateFormatter = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
                dateFormatter.setLenient(false);
                Date today = new Date();
                String datemess = dateFormatter.format(today);
                // Show Alert
                int duration = Toast.LENGTH_LONG;
                if (prefs.getBoolean("child", true)) {
                    db.addSMS(number, body, "1", datemess, id_user);
                }
            }

        }
    }
    private void showSmsToast(Sms sms) {
        Toast.makeText(mcontext, sms.toString(), Toast.LENGTH_LONG).show();

    }
}
