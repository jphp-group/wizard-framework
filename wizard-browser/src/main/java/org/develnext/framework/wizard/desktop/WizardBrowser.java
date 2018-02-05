package org.develnext.framework.wizard.desktop;

import org.cef.CefApp;
import org.cef.CefClient;
import org.cef.CefSettings;
import org.cef.OS;
import org.cef.browser.CefBrowser;
import org.cef.browser.CefFrame;
import org.cef.browser.CefMessageRouter;
import org.cef.callback.CefQueryCallback;
import org.cef.handler.CefAppHandlerAdapter;
import org.cef.handler.CefLifeSpanHandlerAdapter;
import org.cef.handler.CefMessageRouterHandlerAdapter;

import javax.swing.*;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;

public class WizardBrowser extends JFrame {
    private final CefApp cefApp;
    private final CefClient cefClient;
    private final CefBrowser cefBrowser;

    public static void initialize() {
        CefSettings cefSettings = new CefSettings();
        cefSettings.windowless_rendering_enabled = OS.isLinux();

        CefApp.getInstance(cefSettings);
    }

    public WizardBrowser() {
        CefApp.addAppHandler(new CefAppHandlerAdapter(null) {
            @Override
            public void stateHasChanged(CefApp.CefAppState state) {
                // Shutdown the app if the native CEF part is terminated
                if (state == CefApp.CefAppState.TERMINATED) System.exit(0);
            }
        });

        CefSettings cefSettings = new CefSettings();
        cefSettings.windowless_rendering_enabled = OS.isLinux();

        cefApp = CefApp.getInstance(cefSettings);
        cefClient = cefApp.createClient();
        CefMessageRouter messageRouter = CefMessageRouter.create();
        messageRouter.addHandler(new CefMessageRouterHandlerAdapter() {
            @Override
            public boolean onQuery(CefBrowser browser, CefFrame frame, long query_id, String request, boolean persistent, CefQueryCallback callback) {
                return super.onQuery(browser, frame, query_id, request, persistent, callback);
            }
        }, false);

        cefClient.addMessageRouter(messageRouter);

        cefClient.addLifeSpanHandler(new CefLifeSpanHandlerAdapter() {
            @Override
            public void onAfterCreated(CefBrowser browser) {
                super.onAfterCreated(browser);
            }
        });
        cefBrowser = cefClient.createBrowser(null, OS.isLinux(), false);
        getContentPane().add(cefBrowser.getUIComponent());

        pack();
        setSize(800, 600);
        setLocationRelativeTo(null);
        setVisible(true);

        addWindowListener(new WindowAdapter() {
            @Override
            public void windowClosing(WindowEvent e) {
                dispose();
                cefClient.onBeforeClose(cefBrowser);
                cefClient.doClose(cefBrowser);

                cefClient.dispose();
                cefApp.dispose();
            }
        });
    }

    public void loadURL(String url) {
        cefBrowser.stopLoad();
        cefBrowser.loadURL(url);
    }

    public void loadString(String html, String baseUrl) {
        cefBrowser.loadString(html, baseUrl);
    }
}
