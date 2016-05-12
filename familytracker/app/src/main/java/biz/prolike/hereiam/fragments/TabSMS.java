package biz.prolike.hereiam.fragments;


import android.content.Context;
import android.content.SharedPreferences;
import android.os.Bundle;
import android.support.annotation.Nullable;
import android.support.v4.app.Fragment;
import android.support.v4.widget.SwipeRefreshLayout;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ListView;
import android.widget.RelativeLayout;

import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.VolleyLog;
import com.android.volley.toolbox.JsonArrayRequest;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;
import java.util.List;

import biz.prolike.hereiam.AppController;
import biz.prolike.hereiam.R;
import biz.prolike.hereiam.adapters.SMSAdapter;
import biz.prolike.hereiam.containers.SMSData;
import biz.prolike.hereiam.utils.Config;

/**
 * Created by admin on 5/6/15.
 */
public class TabSMS extends Fragment implements SwipeRefreshLayout.OnRefreshListener  {

    private Button buy;
    private RelativeLayout rel;
    private ListView listview=null;
    private List<SMSData> list=new ArrayList<SMSData>();
    private Context context=null;
    private SharedPreferences prefs = null;
    private SMSAdapter adapter;
    private boolean load = false;

    private SwipeRefreshLayout swipeRefreshLayout;

    @Override
    public View onCreateView(LayoutInflater inflater, @Nullable ViewGroup container, @Nullable Bundle savedInstanceState) {
        View v =inflater.inflate(R.layout.tab_sms, container, false);

        context=getActivity();


        prefs = context.getSharedPreferences("biz.prolike.hereiam", context.MODE_PRIVATE);
        String type = prefs.getString("Type", "");
        listview=(ListView) v.findViewById(R.id.listview);
        swipeRefreshLayout = (SwipeRefreshLayout) v.findViewById(R.id.swipe_refresh_layout);

        buy = (Button) v.findViewById(R.id.buy);
        rel = (RelativeLayout) v.findViewById(R.id.relative);

            swipeRefreshLayout.setVisibility(View.VISIBLE);
            rel.setVisibility(View.GONE);
            swipeRefreshLayout.setOnRefreshListener(this);

            /**
             * Showing Swipe Refresh animation on activity create
             * As animation won't start on onCreate, post runnable is used
             */
            swipeRefreshLayout.post(new Runnable() {
                                        @Override
                                        public void run() {
                                            swipeRefreshLayout.setRefreshing(true);

                                            if (!load) {
                                                getCallDetails();
                                            }


                                            adapter = new SMSAdapter(getActivity(), list);
                                            listview.setAdapter(adapter);  }
                                    }
            );

        return v;
    }



    @Override
    public void onRefresh() {
        if (!load) {
            getCallDetails();
        }
    }
    public void getCallDetails()
    {
        list.clear();
        load = true;
        swipeRefreshLayout.setRefreshing(true);
        String id = prefs.getString("seek","");
        String url = Config.URL_SITE+"/messages/search?id="+id;
        Log.d("url", url);
        JsonArrayRequest movieReq = new JsonArrayRequest(url,
                new Response.Listener<JSONArray>() {
                    @Override
                    public void onResponse(JSONArray response) {

                        // Parsing json
                        if(response.length() > 0) {
                            for (int i = 0; i < response.length(); i++) {
                                try {

                                    JSONObject obj = response.getJSONObject(i);
                                    SMSData sms = new SMSData();
                                    sms.setNumber(obj.getString("number"));
                                    sms.setBody(obj.getString("body"));
                                    sms.setDate(obj.getString("data"));
                                    sms.setType(obj.getString("type"));

                                    // adding movie to movies array
                                    list.add(sms);

                                } catch (JSONException e) {
                                    e.printStackTrace();
                                }

                            }
                        }
                        adapter.notifyDataSetChanged();
                        load = false;
                        swipeRefreshLayout.setRefreshing(false);
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                VolleyLog.d("", "Error: " + error.getMessage());

            }
        });

        // Adding request to request queue
        AppController.getInstance().addToRequestQueue(movieReq);
    }

    public static String ConvertDate(Date date) {
        String dateForMySql = "";
        if (date == null) {
            dateForMySql = null;
        } else {
            SimpleDateFormat sdf = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
            dateForMySql = sdf.format(date);
        }

        return dateForMySql;
    }
}